// Non-module version of ui.js for global use
class UIManager {
  constructor() {
    this.modals = {};
  }
  
  init() {
    console.log('Initializing UI Manager');
    this.initModals();
    this.initTooltips();
    this.initDropdowns();
  }
  
  initModals() {
    // Find all modal triggers
    document.querySelectorAll('[data-modal-target]').forEach(trigger => {
      const modalId = trigger.dataset.modalTarget;
      const modal = document.getElementById(modalId);
      
      if (!modal) return;
      
      // Store modal reference
      this.modals[modalId] = {
        element: modal,
        isOpen: false
      };
      
      // Add click event to trigger
      trigger.addEventListener('click', (e) => {
        e.preventDefault();
        this.openModal(modalId);
      });
      
      // Add click events to close buttons
      modal.querySelectorAll('.modal-close').forEach(closeBtn => {
        closeBtn.addEventListener('click', () => {
          this.closeModal(modalId);
        });
      });
      
      // Close modal when clicking outside
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          this.closeModal(modalId);
        }
      });
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        Object.keys(this.modals).forEach(id => {
          if (this.modals[id].isOpen) {
            this.closeModal(id);
          }
        });
      }
    });
  }
  
  openModal(modalId) {
    const modal = this.modals[modalId];
    if (!modal) return;
    
    modal.element.classList.remove('hidden');
    modal.isOpen = true;
    
    // Prevent body scrolling
    document.body.style.overflow = 'hidden';
    
    // Focus first input if exists
    setTimeout(() => {
      const firstInput = modal.element.querySelector('input, textarea, select, button:not(.modal-close)');
      if (firstInput) {
        firstInput.focus();
      }
    }, 100);
  }
  
  closeModal(modalId) {
    const modal = this.modals[modalId];
    if (!modal) return;
    
    modal.element.classList.add('hidden');
    modal.isOpen = false;
    
    // Restore body scrolling if no other modals are open
    const anyModalOpen = Object.values(this.modals).some(m => m.isOpen);
    if (!anyModalOpen) {
      document.body.style.overflow = '';
    }
  }
  
  initTooltips() {
    // Simple tooltip implementation
    document.querySelectorAll('[data-tooltip]').forEach(element => {
      const tooltipText = element.dataset.tooltip;
      
      element.addEventListener('mouseenter', () => {
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = tooltipText;
        
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        tooltip.style.top = `${rect.top - tooltip.offsetHeight - 5}px`;
        tooltip.style.left = `${rect.left + rect.width / 2 - tooltip.offsetWidth / 2}px`;
        
        element.dataset.tooltipId = Date.now();
        tooltip.dataset.tooltipId = element.dataset.tooltipId;
      });
      
      element.addEventListener('mouseleave', () => {
        const tooltipId = element.dataset.tooltipId;
        if (tooltipId) {
          const tooltip = document.querySelector(`.tooltip[data-tooltip-id="${tooltipId}"]`);
          if (tooltip) {
            tooltip.remove();
          }
        }
      });
    });
  }
  
  initDropdowns() {
    // Simple dropdown implementation
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
      toggle.addEventListener('click', (e) => {
        e.preventDefault();
        
        const dropdown = toggle.nextElementSibling;
        if (dropdown && dropdown.classList.contains('dropdown-menu')) {
          dropdown.classList.toggle('show');
        }
      });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.matches('.dropdown-toggle') && !e.target.closest('.dropdown-menu')) {
        document.querySelectorAll('.dropdown-menu.show').forEach(dropdown => {
          dropdown.classList.remove('show');
        });
      }
    });
  }
  
  showToast(message, type = 'info', duration = 3000) {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
      toastContainer = document.createElement('div');
      toastContainer.id = 'toast-container';
      toastContainer.className = 'fixed top-4 right-4 z-50';
      document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type} bg-white shadow-lg rounded-lg p-4 mb-3 flex items-center`;
    
    // Add icon based on type
    let icon = 'info-circle';
    if (type === 'success') icon = 'check-circle';
    if (type === 'error') icon = 'exclamation-circle';
    if (type === 'warning') icon = 'exclamation-triangle';
    
    toast.innerHTML = `
      <i class="fas fa-${icon} mr-2"></i>
      <span>${message}</span>
      <button class="ml-auto text-gray-400 hover:text-gray-600">
        <i class="fas fa-times"></i>
      </button>
    `;
    
    // Add to container
    toastContainer.appendChild(toast);
    
    // Add close button functionality
    const closeBtn = toast.querySelector('button');
    closeBtn.addEventListener('click', () => {
      toast.remove();
    });
    
    // Auto-remove after duration
    setTimeout(() => {
      toast.remove();
    }, duration);
  }
  
  showLoader() {
    let loader = document.getElementById('global-loader');
    if (!loader) {
      loader = document.createElement('div');
      loader.id = 'global-loader';
      loader.className = 'fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50';
      loader.innerHTML = `
        <div class="bg-white p-5 rounded-lg flex flex-col items-center">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2">Loading...</p>
        </div>
      `;
      document.body.appendChild(loader);
    } else {
      loader.classList.remove('hidden');
    }
  }
  
  hideLoader() {
    const loader = document.getElementById('global-loader');
    if (loader) {
      loader.classList.add('hidden');
    }
  }
}