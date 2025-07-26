// Dashboard tab functionality
document.addEventListener('DOMContentLoaded', function() {
  console.log('Dashboard tabs script loaded');
  
  // Tab navigation
  const tabLinks = document.querySelectorAll('[data-tab]');
  const tabContents = document.querySelectorAll('.tab-content');
  
  if (tabLinks.length > 0 && tabContents.length > 0) {
    console.log('Found', tabLinks.length, 'tab links and', tabContents.length, 'tab contents');
    
    // Hide all non-active tabs initially
    tabContents.forEach(tab => {
      if (!tab.classList.contains('active')) {
        tab.style.display = 'none';
      }
    });
    
    // Function to show a specific tab
    function showTab(tabId, updateHash = true) {
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
        const tabLink = document.querySelector(`[data-tab="${tabId}"]`);
        if (tabLink) {
          tabLink.classList.add('active');
        }
        
        // Only update URL hash if not coming from URL parameter
        if (updateHash) {
          window.location.hash = tabId;
        }
        
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
    
    // Check URL hash or parameter on page load
    const hash = window.location.hash.substring(1);
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    let targetTab = null;
    let fromParam = false;
    
    // Check for tab parameter first, then hash
    if (tabParam && document.getElementById(tabParam + '-tab')) {
      targetTab = tabParam;
      fromParam = true;
    } else if (hash && document.getElementById(hash + '-tab')) {
      targetTab = hash;
    }
    
    if (targetTab) {
      showTab(targetTab, !fromParam);
    } else {
      // If no hash or parameter, show dashboard tab by default
      showTab('dashboard');
    }
  }
});