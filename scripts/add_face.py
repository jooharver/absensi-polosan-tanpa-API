#!/usr/bin/env python3
import face_recognition
import json
import sys
import os
from PIL import Image, ImageEnhance, ExifTags

def preprocess_image(image_path):
    # Open the image
    img = Image.open(image_path)
    
    # Fix orientation using EXIF data
    try:
        for orientation in ExifTags.TAGS.keys():
            if ExifTags.TAGS[orientation] == 'Orientation':
                break
        exif = img._getexif()
        if exif is not None and orientation in exif:
            if exif[orientation] == 3:
                img = img.rotate(180, expand=True)
            elif exif[orientation] == 6:
                img = img.rotate(270, expand=True)
            elif exif[orientation] == 8:
                img = img.rotate(90, expand=True)
    except (AttributeError, KeyError, IndexError):
        pass

    # Resize and enhance the image
    img.thumbnail((800, 800))  # Resize to max 800x800
    enhancer = ImageEnhance.Contrast(img)
    img = enhancer.enhance(2.0)  # Increase contrast
    img.save(image_path)

def main():
    # Ensure the script is called with the correct arguments
    if len(sys.argv) < 2:
        print(json.dumps({'error': 'Usage: add_face.py <image_path>'}))
        sys.exit(1)

    image_path = sys.argv[1]

    # Preprocess the image
    preprocess_image(image_path)

    # Load the image and detect faces
    image = face_recognition.load_image_file(image_path)
    face_locations = face_recognition.face_locations(image, model='cnn')  # Use CNN for better accuracy

    if len(face_locations) == 0:
        print(json.dumps({'error': 'No face found in the image.'}))
        sys.exit(1)

    # Select the largest face
    largest_face = max(face_locations, key=lambda rect: (rect[2] - rect[0]) * (rect[1] - rect[3]))
    encoding = face_recognition.face_encodings(image, known_face_locations=[largest_face])[0]

    # Output the face encoding as JSON
    print(json.dumps({
        'face_vector': encoding.tolist()
    }))

if __name__ == '__main__':
    main()
