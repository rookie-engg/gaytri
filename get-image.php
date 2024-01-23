<?php

include 'common/is-session-set.php';

// Get the image name from the query parameter
$imageName = isset($_GET['name']) ? $_GET['name'] : '';

// Path to the "uploads" folder
$uploadsFolder = __DIR__ . DIRECTORY_SEPARATOR . '.' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'customer-images';

// Full path to the image
$imagePath = realpath($uploadsFolder . DIRECTORY_SEPARATOR .$imageName);

// Check if the file exists
if (file_exists($imagePath)) {
  // Set the appropriate content type for an image
  // echo $imagePath;
  header('Content-Type: image/jpeg'); // Adjust the content type based on your image type

  // Read and output the image content
  readfile($imagePath);
} else {
  // Image not found, you can provide a default image or handle the error as needed
  echo 'Image not found';
}
