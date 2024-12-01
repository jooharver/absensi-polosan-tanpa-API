#!/usr/bin/env python3
import sys
import json
import face_recognition
import numpy as np
from PIL import Image, ExifTags, ImageEnhance

def correct_image_orientation(image_path):
    try:
        img = Image.open(image_path)
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
        img.save(image_path)
    except (AttributeError, KeyError, IndexError, TypeError):
        pass

def preprocess_image(image_path):
    img = Image.open(image_path)
    img.thumbnail((800, 800))
    enhancer = ImageEnhance.Contrast(img)
    img = enhancer.enhance(1.5)
    img.save(image_path)

def main():
    if len(sys.argv) < 3:
        print(json.dumps({"matched": False, "error": "Usage: recognize_face.py <image_path> <face_vector_file_path>"}))
        sys.exit(1)

    image_path = sys.argv[1]
    face_vector_file_path = sys.argv[2]  # Fixed variable name

    try:
        # Load the known face vector
        with open(face_vector_file_path, 'r') as f:
            face_vector_json = f.read()
        known_encoding = np.array(json.loads(face_vector_json), dtype=np.float64)

        # Process image
        correct_image_orientation(image_path)
        preprocess_image(image_path)

        # Load and encode face
        unknown_image = face_recognition.load_image_file(image_path)
        face_locations = face_recognition.face_locations(unknown_image, model='hog')

        if len(face_locations) == 0:
            print(json.dumps({"matched": False, "error": "No face found in the image."}))
            sys.exit(1)

        unknown_encoding = face_recognition.face_encodings(unknown_image, known_face_locations=face_locations)[0]

        # Compare faces and convert NumPy boolean to Python boolean
        matches = face_recognition.compare_faces([known_encoding], unknown_encoding, tolerance=0.6)
        match_result = bool(matches[0].item())  # Convert NumPy bool_ to Python bool

        # Output result
        print(json.dumps({"matched": match_result}))

    except FileNotFoundError:
        print(json.dumps({"matched": False, "error": "File not found"}))
        sys.exit(1)
    except Exception as e:
        print(json.dumps({"matched": False, "error": str(e)}))
        sys.exit(1)

if __name__ == "__main__":
    main()