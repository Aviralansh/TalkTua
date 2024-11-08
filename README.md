# TalkTua Chatbot

## Overview

TalkTua is a medical AI-powered chatbot designed to assist users with various health-related inquiries. This project leverages state-of-the-art machine learning models, providing personalized and accurate responses to users. It integrates seamlessly with Vultr's serverless infrastructure for efficient inference, ensuring fast and reliable responses.

## Features

- **Medical AI Assistance**: Provides intelligent responses to health-related queries.
- **Serverless Inference**: Utilizes Vultr’s serverless solution for handling AI inference tasks, offering scalable and cost-effective processing.
- **Seamless Integration**: Can be embedded into websites via an iframe, making it easy to integrate into different platforms.

## Technologies Used

- **Vultr Serverless**: The chatbot uses Vultr's serverless solution for handling AI inference. This allows for easy scalability and efficient resource usage.
- **Laravel**: The backend framework used to manage the chatbot’s operations and API requests.
- **Vue.js**: The frontend technology used to build the chatbot interface.
- **Axios**: For making API requests from the frontend to the serverless API.

## How to Build Locally

To run the project locally, follow these steps:

### Prerequisites

Before you begin, make sure you have the following installed on your machine:

- **PHP** (for Laravel)
- **Composer** (for managing PHP dependencies)
- **Node.js** and **npm** (for frontend dependencies)
- **Git** (for version control)

### Clone the Repository

1. Clone the repository to your local machine:

   ```bash
   git clone https://github.com/Aviralansh/TalkTua.git
   cd TalkTua
   ```

### Setting up the Backend (Laravel)

2. Navigate to the backend directory and install the required PHP dependencies:

   ```bash
   cd backend
   composer install
   ```

3. Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

4. Set up your database and other environment variables in the `.env` file.

5. Generate the application key:

   ```bash
   php artisan key:generate
   ```

6. Run the Laravel development server:

   ```bash
   php artisan serve
   ```

   This will start the Laravel server on `http://localhost:8000`.

### Setting up the Frontend (Vue.js)

7. Navigate to the frontend directory and install the required Node.js dependencies:

   ```bash
   cd frontend
   npm install
   ```

8. Start the Vue.js development server:

   ```bash
   npm run serve
   ```

   This will start the frontend on `http://localhost:8080`.

### Accessing the Chatbot

Once both the backend and frontend are up and running, you should be able to access the chatbot at:

- Frontend: `http://localhost:8080`
- Backend: `http://localhost:8000`

Make sure to configure the frontend to send requests to the correct backend endpoint if necessary.

## License

This project is open-source and available under the [MIT License](LICENSE).
