# Contributing to JobMaker

Thank you for your interest in contributing to JobMaker! This document provides guidelines and instructions for contributing.

## Development Setup

1. Fork the repository
2. Clone your fork:
   ```bash
   git clone https://github.com/YOUR_USERNAME/jobmakerproject.git
   cd jobmakerproject
   ```
3. Follow the setup instructions in [README.md](README.md)

## Code Style

### PHP Code Style
- Follow PSR-12 coding standards
- Use Laravel best practices
- Write descriptive variable and method names
- Add comments for complex logic

### Blade Templates
- Use proper indentation
- Keep views simple and focused
- Extract reusable components

### Database
- Always create migrations for schema changes
- Include proper indexes
- Add foreign key constraints
- Write seeders for test data

## Git Workflow

1. Create a feature branch:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. Make your changes and commit:
   ```bash
   git add .
   git commit -m "Add: description of your changes"
   ```

3. Push to your fork:
   ```bash
   git push origin feature/your-feature-name
   ```

4. Create a Pull Request

## Commit Message Format

Use descriptive commit messages:
- `Add:` for new features
- `Fix:` for bug fixes
- `Update:` for updates to existing features
- `Refactor:` for code refactoring
- `Docs:` for documentation changes

Examples:
```
Add: job application notification system
Fix: employer dashboard statistics calculation
Update: improve job search performance
Refactor: optimize database queries in JobController
Docs: update installation instructions
```

## Testing

Before submitting a PR:

```bash
# Run Laravel tests
make test

# Check for linting issues
make artisan ARGS="pint"

# Clear caches
make clear
```

## Feature Requests

To request a new feature:
1. Open an issue with the label "feature request"
2. Describe the feature and its use case
3. Explain why it would be valuable

## Bug Reports

When reporting bugs, include:
- Steps to reproduce
- Expected behavior
- Actual behavior
- Screenshots (if applicable)
- Environment details (OS, Docker version, etc.)

## Pull Request Guidelines

- Keep PRs focused on a single feature or fix
- Update documentation if needed
- Add or update tests for new features
- Ensure all tests pass
- Follow the existing code style

## Areas for Contribution

### High Priority
- Email notification system
- File upload functionality (CV, logos)
- Job bookmarking feature
- Advanced search filters
- Analytics dashboard

### Medium Priority
- Real-time notifications
- Internal messaging
- Company reviews
- PDF export for applications

### Documentation
- Video tutorials
- API documentation
- Deployment guides
- Best practices guide

## Questions?

If you have questions, feel free to:
- Open an issue for discussion
- Comment on existing issues
- Reach out to maintainers

## Code of Conduct

Be respectful and constructive in all interactions. We're all here to learn and build something great together.

---

Thank you for contributing! 🎉

