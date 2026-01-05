# STREAMFLIX

STREAMFLIX is a modern video streaming application built with Laravel and Vue.js. It allows users to browse movies and series, manage multiple profiles per account, and track their watching progress.

## Tech Stack

- **Backend**: Laravel 11/12
- **Frontend**: Vue.js with Vite
- **Styling**: Tailwind CSS
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **Video Processing**: FFmpeg (via `pbmedia/laravel-ffmpeg`) and video.js
- **Storage**: S3 Compatible (MinIO via Docker)

## Features

- **User Authentication**: Secure login and registration.
- **Multi-Profile Support**: Create and switch between different profiles on a single account.
- **Video Streaming**: Stream movies and series episodes with adaptive quality.
- **Watch Progress**: Automatically track where you left off.
- **Responsive Design**: A sleek, modern UI that works on all devices.

## Getting Started

### Prerequisites

- PHP 8.2+
- Node.js & NPM
- Composer
- FFmpeg (for video processing)
- Docker (for MinIO)

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/streamflix.git
   cd streamflix
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**:
   ```bash
   npm install
   ```

4. **Setup Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Configure your database and other settings in `.env`.

5. **Run Migrations**:
   ```bash
   php artisan migrate
   ```

6. **Setup MinIO (S3 Storage)**:
   Run MinIO using Docker:
   ```bash
   docker run -p 9000:9000 -p 9001:9001 \
     -e "MINIO_ROOT_USER=minioadmin" \
     -e "MINIO_ROOT_PASSWORD=minioadmin" \
     minio/minio server /data --console-address ":9001"
   ```
   Add the following to your `.env` file:
   ```env
   FILESYSTEM_DISK=s3
   AWS_ACCESS_KEY_ID=minioadmin
   AWS_SECRET_ACCESS_KEY=minioadmin
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=streamflix
   AWS_ENDPOINT=http://127.0.0.1:9000
   AWS_USE_PATH_STYLE_ENDPOINT=true
   ```

7. **Build Frontend**:
   ```bash
   npm run build
   ```

### Running the Application

To start the development server:

```bash
composer run dev
```

This command will simultaneously start the Laravel server (`php artisan serve`), the queue listener, and the Vite development server.

## Testing

Run the test suite with:

```bash
composer run test
```

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
