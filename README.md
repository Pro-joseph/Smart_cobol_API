# 🚀 Smart COBOL API

> AI-powered COBOL to Laravel API converter - Modernize legacy COBOL code into production-ready REST APIs

[![Laravel](https://img.shields.io/badge/Laravel-13.0-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## 📋 Overview

Smart COBOL API is a web application that automatically converts legacy COBOL code into modern Laravel REST APIs using AI assistance. Upload your COBOL files, and get production-ready Laravel code with built-in testing capabilities.

### ✨ Key Features

- 📤 **COBOL File Upload** - Support for .cbl, .cob, and .txt files
- 🔍 **Intelligent Parser** - Extracts ADD/SUBTRACT operations from COBOL code
- 🤖 **AI Code Generation** - Powered by IBM watsonx.ai (Granite 13B model)
- 🔄 **Fallback Generator** - Works without AI with template-based generation
- 🧪 **Live API Testing** - Built-in test interface with real-time execution
- 🎨 **Modern UI** - Responsive design with beautiful gradients and animations
- ⚡ **Fast Setup** - One-command installation and development

## 🛠️ Tech Stack

- **Backend:** PHP 8.3, Laravel 13
- **Frontend:** Vite 8.0, Tailwind CSS 4.0
- **Database:** SQLite (default)
- **AI:** IBM watsonx.ai (optional)
- **Testing:** PHPUnit, Mockery

## 📦 Installation

### Prerequisites

- PHP 8.3 or higher
- Composer
- Node.js & NPM
- SQLite (or your preferred database)

### Quick Start

```bash
# Clone the repository
git clone https://github.com/yourusername/smart-cobol-api.git
cd smart-cobol-api

# Install dependencies and setup
composer setup

# Start development server
composer dev
```

The application will be available at `http://localhost:8000`

### Manual Setup

```bash
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Install frontend dependencies
npm install

# Build assets
npm run build

# Start server
php artisan serve
```

## ⚙️ Configuration

### Environment Variables

Create a `.env` file from `.env.example` and configure:

```env
# Application
APP_NAME="Smart COBOL API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (SQLite by default)
DB_CONNECTION=sqlite

# IBM watsonx.ai (Optional - uses fallback if not configured)
IBM_WATSONX_API_KEY=your_api_key_here
IBM_WATSONX_PROJECT_ID=your_project_id_here
IBM_WATSONX_URL=https://us-south.ml.cloud.ibm.com
```

### Getting IBM watsonx.ai Credentials

1. Sign up at [IBM Cloud](https://cloud.ibm.com)
2. Create a watsonx.ai project
3. Generate an API key from IAM
4. Copy your project ID from watsonx.ai dashboard

**Note:** The application works without AI credentials using the fallback generator.

## 🎯 Usage

### 1. Upload COBOL File

- Navigate to `http://localhost:8000`
- Click "Choose COBOL File"
- Select your .cbl, .cob, or .txt file
- Click "Generate API"

### 2. View Parsed Operations

The parser extracts operations like:
- `ADD AMOUNT TO BALANCE`
- `SUBTRACT TAX FROM BALANCE`

### 3. Get Generated Code

Receive production-ready Laravel code including:
- Service class with business logic
- Controller with validation
- API route definitions

### 4. Test Live

Use the built-in testing interface to:
- Input test values
- Execute operations
- View JSON responses in real-time

## 📁 Project Structure

```
smart-cobol-api/
├── app/
│   ├── Http/Controllers/
│   │   └── CobolController.php      # Main controller
│   ├── Services/
│   │   ├── CobolParser.php          # COBOL parsing logic
│   │   └── AiCobolGenerator.php     # AI code generation
│   └── Models/
├── resources/
│   └── views/
│       └── main.blade.php           # Main UI
├── routes/
│   └── web.php                      # Application routes
├── storage/
│   └── app/
│       └── public/cobol/            # Uploaded COBOL files
├── tests/                           # Test suite
├── code.cbl                         # Sample COBOL file
└── README.md
```

## 🔧 Development

### Available Commands

```bash
# Start development environment (server + queue + vite)
composer dev

# Run tests
composer test

# Code formatting
./vendor/bin/pint

# Clear caches
php artisan optimize:clear
```

### API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Main upload interface |
| POST | `/generate` | Process COBOL and generate API |
| POST | `/cobol/test` | Test generated API with data |
| POST | `/cobol/run` | Execute operations (planned) |

## 🧪 Testing

Run the test suite:

```bash
composer test
```

Or with PHPUnit directly:

```bash
./vendor/bin/phpunit
```

## 📝 Sample COBOL Code

The included `code.cbl` demonstrates supported operations:

```cobol
ADD AMOUNT TO BALANCE.
SUBTRACT TAX FROM BALANCE.

IF BALANCE > 1000
   SUBTRACT TAX FROM BALANCE
END-IF.

ADD INCOME TO BALANCE.
SUBTRACT RENT FROM BALANCE.
ADD REFUND TO BALANCE.
SUBTRACT PENALTY FROM BALANCE.
```

## 🤖 How It Works

### 1. COBOL Parser
- Uses regex patterns to identify operations
- Extracts variables and operation types
- Deduplicates using MD5 hashing
- Returns structured JSON

### 2. AI Code Generation
- Sends parsed operations to IBM watsonx.ai
- Uses Granite 13B Chat model
- Generates complete Laravel code
- Falls back to template if AI unavailable

### 3. Fallback Generator
- Creates service class with execute() method
- Generates controller with validation
- Provides API route definitions
- Ensures application always works

## 🚀 Deployment

### Production Setup

```bash
# Set environment to production
APP_ENV=production
APP_DEBUG=false

# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build
```

### Server Requirements

- PHP 8.3+
- Composer
- Web server (Apache/Nginx)
- SQLite or MySQL/PostgreSQL
- Node.js (for asset compilation)

## 🔐 Security

- CSRF protection enabled
- File upload validation
- Input sanitization
- Environment variable protection
- Secure API token handling

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📈 Roadmap

- [ ] Support for more COBOL operations (MULTIPLY, DIVIDE, MOVE)
- [ ] Parse COBOL data structures (WORKING-STORAGE)
- [ ] Database persistence for generated APIs
- [ ] Multiple AI provider support (OpenAI, Anthropic)
- [ ] Syntax highlighting for generated code
- [ ] Export functionality (ZIP download)
- [ ] API documentation generation
- [ ] Batch file processing

## 🐛 Known Issues

- Parser currently supports only ADD/SUBTRACT operations
- Conditional logic (IF statements) not fully processed
- `/cobol/run` endpoint not yet implemented

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👥 Authors

- **Your Name** - Initial work

## 🙏 Acknowledgments

- Laravel Framework team
- IBM watsonx.ai team
- COBOL community
- Open source contributors

## 📞 Support

For support, email support@example.com or open an issue on GitHub.

## 🔗 Links

- [Laravel Documentation](https://laravel.com/docs)
- [IBM watsonx.ai](https://www.ibm.com/watsonx)
- [Tailwind CSS](https://tailwindcss.com)
- [Vite](https://vitejs.dev)

---

<p align="center">Made with ❤️ for COBOL modernization</p>
