// EmailJS configuration
emailjs.init("QTreaie3I9i7MDS1C");

// Email templates - using your existing working template for now
const EMAIL_TEMPLATES = {
    password_reset: 'template_daog74s',
    order_confirmation: 'template_v6ol2tu',
    generic: 'template_v6ol2tu'
};

// Send email function
async function sendPendingEmail() {
    try {
        const response = await fetch('/api/get-pending-email.php');
        const emailData = await response.json();
        
        if (emailData && emailData.to) {
            let templateParams = {};
            let templateId = '';
            
            // Determine email type and set parameters
            if (emailData.type === 'password_reset') {
                templateId = EMAIL_TEMPLATES.password_reset;
                templateParams = {
                    email: emailData.to,
                    message: `Password reset link: ${emailData.reset_link}`
                };
            } else if (emailData.type === 'order_confirmation') {
                templateId = EMAIL_TEMPLATES.order_confirmation;
                const orderMessage = `Order #${emailData.order_id} - Customer: ${emailData.customer_name} - Total: ₦${emailData.total} - Items: ${emailData.items}`;
                templateParams = {
                    email: emailData.to,
                    message: orderMessage
                };
            } else {
                // Fallback for generic emails
                templateId = 'template_4zy7k7n';
                templateParams = {
                    to_name: emailData.to,
                    to_email: emailData.to,
                    from_name: 'DeeReel Footies',
                    message: emailData.body
                };
            }
            
            console.log('Sending email:', emailData.type, templateParams);
            
            const result = await emailjs.send('service_wur0urq', templateId, templateParams);
            console.log('Email sent successfully via EmailJS:', result);
        }
    } catch (error) {
        console.error('EmailJS error:', error);
    }
}

// Auto-send pending emails when page loads
document.addEventListener('DOMContentLoaded', function() {
    sendPendingEmail();
});