// subcategory-links.js - Handles subcategory links to main category pages with filters
document.addEventListener('DOMContentLoaded', function() {
  // Get the current page path
  const path = window.location.pathname;
  const urlParams = new URLSearchParams(window.location.search);
  
  // Check if we're on a product category page
  if (path.includes('-shoes.php') || path.includes('-boots.php') || 
      path.includes('-mules.php') || path.includes('-slippers.php')) {
    
    // Highlight active type filter in mobile view
    const typeParam = urlParams.get('type');
    if (typeParam && typeParam !== 'all') {
      document.querySelectorAll('.type-filter').forEach(btn => {
        if (btn.dataset.type.toLowerCase() === typeParam.toLowerCase()) {
          btn.classList.add('bg-gray-800', 'text-white');
        }
      });
    }
  }
  
  // Check if we're on a product page
  else if (path.includes('/product.php')) {
    // Extract the product type from the page data
    const productType = document.body.getAttribute('data-page');
    
    if (productType) {
      // Determine the subcategory type
      let subcategoryType = '';
      
      if (productType.includes('oxford')) {
        subcategoryType = 'oxford';
      } else if (productType.includes('loafer')) {
        subcategoryType = 'loafers';
      } else if (productType.includes('derby')) {
        subcategoryType = 'derby';
      } else if (productType.includes('monk')) {
        subcategoryType = 'monk';
      } else if (productType.includes('chelsea')) {
        subcategoryType = 'chelsea';
      } else if (productType.includes('jodhpur')) {
        subcategoryType = 'jodhpur';
      } else if (productType.includes('slide')) {
        subcategoryType = 'slide';
      } else if (productType.includes('captoe')) {
        subcategoryType = 'captoe';
      } else if (productType.includes('wingtip')) {
        subcategoryType = 'wingtip';
      } else if (productType.includes('zipper')) {
        subcategoryType = 'zipper';
      } else if (productType.includes('balmoral')) {
        subcategoryType = 'balmoral';
      } else if (productType.includes('ankle')) {
        subcategoryType = 'ankle';
      } else if (productType.includes('knee')) {
        subcategoryType = 'knee';
      } else if (productType.includes('riding')) {
        subcategoryType = 'riding';
      }
      
      // Determine the gender
      let gender = '';
      if (path.includes('men') || productType.includes('men')) {
        gender = 'men';
      } else if (path.includes('women') || productType.includes('women')) {
        gender = 'women';
      }
      
      // Determine the main category
      let mainCategory = '';
      if (productType.includes('shoe')) {
        mainCategory = `/products/${gender}/${gender}-shoes.php`;
      } else if (productType.includes('boot')) {
        mainCategory = `/products/${gender}/${gender}-boots.php`;
      } else if (productType.includes('mule')) {
        mainCategory = `/products/${gender}/${gender}-mules.php`;
      } else if (productType.includes('slipper')) {
        mainCategory = `/products/${gender}/${gender}-slippers.php`;
      }
      
      // Update breadcrumb links if we have a subcategory type and main category
      if (subcategoryType && mainCategory) {
        // Find the breadcrumb links
        const breadcrumbLinks = document.querySelectorAll('.flex.items-center.text-sm.text-gray-500 a');
        
        // Find the category link (e.g., "Men" or "Women")
        breadcrumbLinks.forEach(link => {
          const href = link.getAttribute('href');
          if (href === '/men.php' || href === '/women.php') {
            // Find the next span (which should be the separator)
            let nextElement = link.nextElementSibling;
            if (nextElement && nextElement.tagName === 'SPAN') {
              // Find the next element after the separator (which should be the category name)
              let categoryElement = nextElement.nextElementSibling;
              
              // If it's a span, replace it with a link to the main category with filter
              if (categoryElement && categoryElement.tagName === 'SPAN') {
                const categoryName = categoryElement.textContent;
                const newLink = document.createElement('a');
                newLink.href = `${mainCategory}?gender=${gender}&type=${subcategoryType}`;
                newLink.textContent = categoryName;
                categoryElement.parentNode.replaceChild(newLink, categoryElement);
              }
            }
          }
        });
      }
    }
  }
});