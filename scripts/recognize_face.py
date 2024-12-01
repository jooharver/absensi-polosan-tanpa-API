#!/usr/bin/env python3
import sys
import face_recognition
import json
import os
import requests
import numpy as np
from PIL import Image, ExifTags, ImageEnhance

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
    img.thumbnail((800, 800))  # Resize to max 800x800
    enhancer = ImageEnhance.Contrast(img)
    img = enhancer.enhance(1.5)  # Increase contrast
    img.save(image_path)

def send_to_laravel(karyawan_id, encoding):
    """Send the encoding data to Laravel via API."""
    url = "http://192.168.1.40:8000/api/face-add"  # Laravel endpoint
    payload = {
        "karyawan_id": karyawan_id,
        "face_vector": encoding.tolist(),
    }

    try:
        response = requests.post(url, json=payload)
        if response.status_code == 200:
            print(f"Successfully sent data for Karyawan ID {karyawan_id}")
        else:
            print(f"Failed to send data: {response.text}")
    except requests.RequestException as e:
        print(f"Error communicating with Laravel: {e}")

def main():
    if len(sys.argv) < 3:
        print("Usage: process_face.py <image_path> <karyawan_id>")
        sys.exit(1)

    image_path = sys.argv[1]
    karyawan_id = sys.argv[2]

    correct_image_orientation(image_path)
    preprocess_image(image_path)

    unknown_image = face_recognition.load_image_file(image_path)
    face_locations = face_recognition.face_locations(unknown_image, model='hog')  # Faster model

    if len(face_locations) == 0:
        print("No face found in the image.")
        sys.exit(1)

    try:
        unknown_encoding = face_recognition.face_encodings(unknown_image, known_face_locations=face_locations)[0]
    except IndexError:
        print("No face encoding found.")
        sys.exit(1)

    send_to_laravel(karyawan_id, unknown_encoding)

if __name__ == "__main__":
    main()