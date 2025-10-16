# Contributing to TS4.in

First off, thank you for considering contributing to TS4.in! It's people like you that make TS4.in such a great tool.

## Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code.

## How Can I Contribute?

### Reporting Bugs
- Use a clear and descriptive title
- Describe the exact steps which reproduce the problem
- Include screenshots and animated GIFs if possible
- Specify your environment (OS, PHP version, etc.)

### Suggesting Enhancements
- Use a clear and descriptive title
- Provide a detailed description of the suggested enhancement
- List some examples showing the use case

### Pull Requests
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Development Setup

```bash
# Clone your fork
git clone https://github.com/your-username/ts4-in.git
cd ts4-in

# Setup environment
cp .env.example .env
php artisan key:generate

# Install dependencies
composer install
npm install

# Run migrations
php artisan migrate

# Start development server
php artisan serve
npm run dev
```

## Guidelines

### Code Style
- Follow PSR-12 coding standards
- Use snake_case for database and variables
- Use camelCase for PHP methods
- Use Vue 3 Composition API for components

### Commit Messages
- Use the present tense ("Add feature" not "Added feature")
- Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit the first line to 72 characters
- Reference issues and pull requests liberally after the first line

### Testing
- Write tests for all new features
- Run `php artisan test` before submitting
- Aim for >80% code coverage

### Documentation
- Update README if needed
- Add docstrings to public methods
- Update API documentation

## Labels

- `good first issue` - Good for newcomers
- `help wanted` - Specific extra attention
- `bug` - Something isn't working
- `enhancement` - New feature or request
- `documentation` - Improvements or additions to documentation

## Recognition

Contributors will be recognized in our [CONTRIBUTORS.md](CONTRIBUTORS.md) file!

---

Questions? Start a [Discussion](../../discussions) or email support@ts4.in
