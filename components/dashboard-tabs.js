// Dashboard tab functionality
document.addEventListener('DOMContentLoaded', function() {
  console.log('Dashboard tabs script loaded');
  
  // Tab navigation
  const tabLinks = document.querySelectorAll('[data-tab]');
  const tabContents = document.querySelectorAll('.tab-content');
  
  if (tabLinks.length > 0 && tabContents.length > 0) {
    console.log('Found', tabLinks.length, 'tab links and', tabContents.length, 'tab contents');
    
    // Function to show a specific tab
    function showTab(tabId) {
      console.log('Showing tab:', tabId);
      
      // Hide all tabs
      tabContents.forEach(tab => {
        tab.style.display = 'none';
      });
      
      // Remove active class from all tab links
      tabLinks.forEach(link => {
        link.classList.remove('active');
      });
      
      // Show selected tab
      const targetTab = document.getElementById(tabId + '-tab');
      if (targetTab) {
        targetTab.style.display = 'block';
        
        // Add active class to tab link
        document.querySelector(`[data-tab="${tabId}"]`).classList.add('active');
        
        // Update URL hash
        window.location.hash = tabId;
        
        console.log('Tab displayed:', tabId);
      } else {
        console.error('Tab not found:', tabId);
      }
    }
    
    // Add click event to all tab links
    tabLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const tabId = this.getAttribute('data-tab');
        showTab(tabId);
      });
    });
    
    // Check URL hash on page load
    const hash = window.location.hash.substring(1);
    if (hash && document.getElementById(hash + '-tab')) {
      showTab(hash);
    }
  }
});