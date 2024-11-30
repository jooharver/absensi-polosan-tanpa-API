#!/usr/bin/env python3
import sys
import face_recognition
import json
import os
import numpy as np
from PIL import Image, ExifTags, ImageEnhance, ImageDraw

def correct_image_orientation(image_path):
    """Correct the orientation of the image using EXIF metadata."""
    img = Image.open(image_path)
    try:
        for orientation in ExifTags.TAGS.keys():
            if ExifTags.TAGS[orientation] == 'Orientation':
                break
        exif = img._getexif()
        if exif and orientation in exif:
            if exif[orientation] == 3:
                img = img.rotate(180, expand=True)
            elif exif[orientation] == 6:
                img = img.rotate(270, expand=True)
            elif exif[orientation] == 8:
                img = img.rotate(90, expand=True)
    except (AttributeError, KeyError, IndexError):
        pass
    img.save(image_path)

def preprocess_image(image_path):
    """Enhance contrast and resize the image."""
    img = Image.open(image_path)
    img = img.resize((800, 800))  # Resize to 800x800
    enhancer = ImageEnhance.Contrast(img)
    img = enhancer.enhance(1.5)  # Increase contrast
    img.save(image_path)

def draw_face_locations(image_path, face_locations):
    """Draw rectangles around detected faces for debugging."""
    img = Image.open(image_path)
    draw = ImageDraw.Draw(img)
    for top, right, bottom, left in face_locations:
        draw.rectangle(((left, top), (right, bottom)), outline="red", width=3)
    img.show()

def load_known_faces(known_faces_file):
    """Load known face encodings and names from a JSON file."""
    if not os.path.exists(known_faces_file):
        raise FileNotFoundError('Known faces file not found')
    
    with open(known_faces_file, 'r') as f:
        known_faces = json.load(f)
    
    known_encodings = []
    known_names = []
    for person in known_faces:
        known_encodings.append(np.array(person['encoding']))
        known_names.append(person['name'])
    
    return known_encodings, known_names

def main():
    if len(sys.argv) < 2:
        print(json.dumps({'error': 'No image path provided'}))
        sys.exit(1)

    image_path = sys.argv[1]
    tolerance = float(sys.argv[2]) if len(sys.argv) > 2 else 0.5  # Default tolerance 0.5

    # Correct image orientation and preprocess
    correct_image_orientation(image_path)
    preprocess_image(image_path)

    # Load the uploaded image
    unknown_image = face_recognition.load_image_file(image_path)
    face_locations = face_recognition.face_locations(unknown_image, model='hog')  # Using HOG for faster processing

    if len(face_locations) == 0:
        print(json.dumps({'matched': False, 'message': 'No face found in the image'}))
        sys.exit(0)

    # Draw face locations for debugging (Optional)
    draw_face_locations(image_path, face_locations)

    try:
        unknown_encoding = face_recognition.face_encodings(unknown_image)[0]
    except IndexError:
        print(json.dumps({'matched': False, 'message': 'No face encoding found'}))
        sys.exit(0)

    # Load known faces
    known_faces_file = os.path.join(os.path.dirname(__file__), 'known_faces.json')
    try:
        known_encodings, known_names = load_known_faces(known_faces_file)
    except FileNotFoundError as e:
        print(json.dumps({'error': str(e)}))
        sys.exit(1)

    # Compare faces
    results = face_recognition.compare_faces(known_encodings, unknown_encoding, tolerance=tolerance)
    face_distances = face_recognition.face_distance(known_encodings, unknown_encoding)

    if True in results:
        best_match_index = np.argmin(face_distances)
        matched_name = known_names[best_match_index]
        print(json.dumps({'matched': True, 'name': matched_name, 'distance': face_distances[best_match_index]}))
    else:
        print(json.dumps({'matched': False, 'message': 'No match found'}))

if __name__ == '__main__':
    main()