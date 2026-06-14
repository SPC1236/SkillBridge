// Form validation specific functions

class FormValidator {
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.fields = [];
        this.init();
    }
    
    init() {
        if (!this.form) return;
        
        // Find all validatable fields
        this.fields = Array.from(this.form.querySelectorAll('[data-validate]'));
        
        // Add event listeners
        this.fields.forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('input', () => this.clearFieldError(field));
        });
        
        // Form submission validation
        this.form.addEventListener('submit', (e) => {
            if (!this.validateAll()) {
                e.preventDefault();
                this.showFormError('Please fix the errors before submitting.');
            }
        });
    }
    
    validateField(field) {
        const value = field.value.trim();
        const rules = field.getAttribute('data-validate').split(' ');
        
        for (let rule of rules) {
            const isValid = this.checkRule(rule, value, field);
            if (!isValid) {
                return false;
            }
        }
        
        this.markFieldValid(field);
        return true;
    }
    
    checkRule(rule, value, field) {
        switch (rule) {
            case 'required':
                if (!value) {
                    this.showFieldError(field, 'This field is required');
                    return false;
                }
                break;
                
            case 'email':
                if (value && !this.isValidEmail(value)) {
                    this.showFieldError(field, 'Please enter a valid email address');
                    return false;
                }
                break;
                
            case 'password':
                if (value && value.length < 6) {
                    this.showFieldError(field, 'Password must be at least 6 characters');
                    return false;
                }
                break;
                
            case 'phone':
                if (value && !this.isValidPhone(value)) {
                    this.showFieldError(field, 'Please enter a valid phone number');
                    return false;
                }
                break;
                
            case 'url':
                if (value && !this.isValidUrl(value)) {
                    this.showFieldError(field, 'Please enter a valid URL');
                    return false;
                }
                break;
        }
        
        return true;
    }
    
    isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    isValidPhone(phone) {
        const re = /^[\+]?[1-9][\d]{0,15}$/;
        return re.test(phone.replace(/[\s\-\(\)]/g, ''));
    }
    
    isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch (_) {
            return false;
        }
    }
    
    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.style.borderColor = '#ef4444';
        field.style.backgroundColor = '#fef2f2';
        
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error-message';
        errorElement.style.cssText = `
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        `;
        
        errorElement.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zM8 4a1 1 0 0 0-1 1v4a1 1 0 0 0 2 0V5a1 1 0 0 0-1-1zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" fill="currentColor"/>
            </svg>
            ${message}
        `;
        
        field.parentNode.appendChild(errorElement);
    }
    
    clearFieldError(field) {
        field.style.borderColor = '';
        field.style.backgroundColor = '';
        
        const existingError = field.parentNode.querySelector('.field-error-message');
        if (existingError) {
            existingError.remove();
        }
    }
    
    markFieldValid(field) {
        field.style.borderColor = '#10b981';
        field.style.backgroundColor = '#f0fdf4';
    }
    
    validateAll() {
        let isValid = true;
        
        this.fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    showFormError(message) {
        // Remove existing form errors
        const existingErrors = this.form.querySelectorAll('.form-error-message');
        existingErrors.forEach(error => error.remove());
        
        // Create new error message
        const errorElement = document.createElement('div');
        errorElement.className = 'form-error-message alert alert-error';
        errorElement.textContent = message;
        
        this.form.insertBefore(errorElement, this.form.firstChild);
        
        // Scroll to error
        errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

// Password strength indicator
class PasswordStrength {
    constructor(passwordField, strengthIndicator) {
        this.passwordField = passwordField;
        this.strengthIndicator = strengthIndicator;
        this.init();
    }
    
    init() {
        this.passwordField.addEventListener('input', () => {
            const strength = this.calculateStrength(this.passwordField.value);
            this.updateIndicator(strength);
        });
    }
    
    calculateStrength(password) {
        let score = 0;
        
        if (password.length >= 8) score++;
        if (password.length >= 12) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^a-zA-Z0-9]/.test(password)) score++;
        
        return Math.min(score, 5);
    }
    
    updateIndicator(strength) {
        const levels = [
            { text: 'Very Weak', color: '#ef4444', width: '20%' },
            { text: 'Weak', color: '#f97316', width: '40%' },
            { text: 'Fair', color: '#eab308', width: '60%' },
            { text: 'Good', color: '#84cc16', width: '80%' },
            { text: 'Strong', color: '#10b981', width: '100%' }
        ];
        
        const level = levels[strength - 1] || levels[0];
        
        this.strengthIndicator.innerHTML = `
            <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 0.5rem;">
                <span style="font-size: 0.875rem;">Password Strength:</span>
                <span style="font-size: 0.875rem; font-weight: 600; color: ${level.color}">${level.text}</span>
            </div>
            <div style="background: #e5e7eb; height: 4px; border-radius: 2px; overflow: hidden;">
                <div style="width: ${level.width}; height: 100%; background: ${level.color}; transition: all 0.3s ease;"></div>
            </div>
        `;
    }
}

// Initialize validators when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form validators
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        new FormValidator(form.id);
    });
    
    // Initialize password strength indicators
    const passwordFields = document.querySelectorAll('input[type="password"][data-strength]');
    passwordFields.forEach(field => {
        const indicatorId = field.getAttribute('data-strength');
        const indicator = document.getElementById(indicatorId);
        if (indicator) {
            new PasswordStrength(field, indicator);
        }
    });
});

// Export for global use
window.FormValidator = FormValidator;
window.PasswordStrength = PasswordStrength;