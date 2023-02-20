<?php

use Aws\S3\S3Client;
use Aws\Ses\SesClient;

// AWS S3
function connectS3() {
  $connection = new S3Client([
    'version' => 'latest',
    'region' => $_ENV['AWS_DEFAULT_REGION'],
    'profile' => 'default',
  ]);

  return $connection;
}

function uploadS3(string $fileName, string $body = '') {
  $aws = connectS3();

  if ($body != '') {
    $aws->putObject([
      'Bucket' => $_ENV['AWS_BUCKET'],
      'Key' => 'assets/' . $fileName,
      'body' => $body,
    ]);
  } else {
    $aws->putObject([
      'Bucket' => $_ENV['AWS_BUCKET'],
      'Key' => 'assets/' . $fileName,
      'SourceFile' => 'assets/' . $fileName,
    ]);

    File::delete(public_path() . "/assets/" . $fileName);
  }
}

function deleteS3(string $fileName) {
  $aws = connectS3();

  $aws->deleteObject([
    'Bucket' => $_ENV['AWS_BUCKET'],
    'Key' => 'assets/' . $fileName,
  ]);
}

// AWS SES
function connectSes() {
  $connection = new SesClient([
    'version' => 'latest',
    'region' => $_ENV['AWS_DEFAULT_REGION'],
    'profile' => 'default',
  ]);

  return $connection;
}

function sendEmailSes(string $to, string $from, string $subject, string $body) {
  $aws = connectSes();

  $aws->sendEmail([
    'Destination' => [
      'ToAddresses' => [$to],
    ],
    'Source' => $from,
    'Message' => [
      'Body' => [
        'Html' => [
          'Charset' => 'UTF-8',
          'Data' => $body,
        ],
      ],
      'Subject' => [
        'Charset' => 'UTF-8',
        'Data' => $subject,
      ],
    ],
  ]);
}