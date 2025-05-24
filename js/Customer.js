// customer.js
import { $ } from './ui.js';

export const saveCustomerInfo = () => {
  const name = $('#client-name')?.value.trim();
  const address = $('#shipping-address')?.value.trim();
  const file = $('#payment-proof')?.files[0];

  if (!name || !address || !file) {
    alert('Please fill all fields.');
    return;
  }

  const formData = new FormData();
  formData.append('name', name);
  formData.append('address', address);
  formData.append('proof', file);

  fetch('/drf/save-customer.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('Customer info saved to server!');
      } else {
        alert('Error saving: ' + data.error);
      }
    });

  const reader = new FileReader();
  reader.onload = function () {
    const customerInfo = {
      name,
      address,
      proof: reader.result
    };
    localStorage.setItem('DRFCustomerInfo', JSON.stringify(customerInfo));
  };

  if (file) reader.readAsDataURL(file);
};
