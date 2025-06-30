// Countries and their states/provinces data
const COUNTRIES_STATES = {
  'Nigeria': [
    'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
    'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT Abuja', 'Gombe',
    'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos',
    'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto',
    'Taraba', 'Yobe', 'Zamfara'
  ],
  'Ghana': [
    'Ashanti', 'Brong Ahafo', 'Central', 'Eastern', 'Greater Accra', 'Northern', 
    'Upper East', 'Upper West', 'Volta', 'Western'
  ],
  'Kenya': [
    'Baringo', 'Bomet', 'Bungoma', 'Busia', 'Elgeyo-Marakwet', 'Embu', 'Garissa',
    'Homa Bay', 'Isiolo', 'Kajiado', 'Kakamega', 'Kericho', 'Kiambu', 'Kilifi',
    'Kirinyaga', 'Kisii', 'Kisumu', 'Kitui', 'Kwale', 'Laikipia', 'Lamu', 'Machakos',
    'Makueni', 'Mandera', 'Marsabit', 'Meru', 'Migori', 'Mombasa', 'Murang\'a',
    'Nairobi', 'Nakuru', 'Nandi', 'Narok', 'Nyamira', 'Nyandarua', 'Nyeri',
    'Samburu', 'Siaya', 'Taita-Taveta', 'Tana River', 'Tharaka-Nithi', 'Trans Nzoia',
    'Turkana', 'Uasin Gishu', 'Vihiga', 'Wajir', 'West Pokot'
  ],
  'South Africa': [
    'Eastern Cape', 'Free State', 'Gauteng', 'KwaZulu-Natal', 'Limpopo',
    'Mpumalanga', 'Northern Cape', 'North West', 'Western Cape'
  ],
  'United States': [
    'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut',
    'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa',
    'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan',
    'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
    'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio',
    'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota',
    'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia',
    'Wisconsin', 'Wyoming'
  ],
  'United Kingdom': [
    'England', 'Scotland', 'Wales', 'Northern Ireland'
  ],
  'Canada': [
    'Alberta', 'British Columbia', 'Manitoba', 'New Brunswick', 'Newfoundland and Labrador',
    'Northwest Territories', 'Nova Scotia', 'Nunavut', 'Ontario', 'Prince Edward Island',
    'Quebec', 'Saskatchewan', 'Yukon'
  ],
  'Germany': [
    'Baden-Württemberg', 'Bavaria', 'Berlin', 'Brandenburg', 'Bremen', 'Hamburg',
    'Hesse', 'Lower Saxony', 'Mecklenburg-Vorpommern', 'North Rhine-Westphalia',
    'Rhineland-Palatinate', 'Saarland', 'Saxony', 'Saxony-Anhalt', 'Schleswig-Holstein',
    'Thuringia'
  ],
  'France': [
    'Auvergne-Rhône-Alpes', 'Bourgogne-Franche-Comté', 'Brittany', 'Centre-Val de Loire',
    'Corsica', 'Grand Est', 'Hauts-de-France', 'Île-de-France', 'Normandy', 'Nouvelle-Aquitaine',
    'Occitanie', 'Pays de la Loire', 'Provence-Alpes-Côte d\'Azur'
  ],
  'Australia': [
    'Australian Capital Territory', 'New South Wales', 'Northern Territory', 'Queensland',
    'South Australia', 'Tasmania', 'Victoria', 'Western Australia'
  ]
};

// Function to populate states based on selected country
function populateStates(countryValue, stateSelectId = 'state-select', defaultState = '') {
  const stateSelect = document.getElementById(stateSelectId);
  if (!stateSelect) return;
  
  // Clear existing options
  stateSelect.innerHTML = '<option value="">Select State</option>';
  
  if (countryValue && COUNTRIES_STATES[countryValue]) {
    const states = COUNTRIES_STATES[countryValue];
    states.forEach(state => {
      const option = document.createElement('option');
      option.value = state;
      option.textContent = state;
      if (state === defaultState) {
        option.selected = true;
      }
      stateSelect.appendChild(option);
    });
    
    // Default to Lagos for Nigeria if no default state specified
    if (countryValue === 'Nigeria' && !defaultState) {
      stateSelect.value = 'Lagos';
    }
  }
}

// Initialize country/state functionality
function initializeCountryStateDropdowns() {
  const countrySelect = document.getElementById('country-select');
  const stateSelect = document.getElementById('state-select');
  
  if (countrySelect && stateSelect) {
    // Populate states for default country on page load
    const defaultCountry = countrySelect.value;
    if (defaultCountry) {
      populateStates(defaultCountry);
    }
    
    // Update states when country changes
    countrySelect.addEventListener('change', function() {
      populateStates(this.value);
      // Update shipping progress when country changes
      if (typeof updateShippingProgress === 'function') {
        setTimeout(updateShippingProgress, 100); // Small delay to let state dropdown update
      }
    });
    
    // Update shipping progress when state changes
    stateSelect.addEventListener('change', function() {
      if (typeof updateShippingProgress === 'function') {
        updateShippingProgress();
      }
    });
  }
}

// Make functions globally available
window.populateStates = populateStates;
window.initializeCountryStateDropdowns = initializeCountryStateDropdowns;
window.COUNTRIES_STATES = COUNTRIES_STATES;
