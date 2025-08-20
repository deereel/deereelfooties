// Exit intent popup to retain visitors
class ExitIntentPopup {
    constructor() {
        this.shown = sessionStorage.getItem('exitPopupShown') === 'true';
        this.init();
    }
    
    init() {
        if (!this.shown) {
            document.addEventListener('mouseleave', (e) => this.handleMouseLeave(e));
            this.createPopup();
        }
    }
    
    handleMouseLeave(e) {
        if (e.clientY <= 0 && !this.shown) {
            this.showPopup();
        }
    }
    
    createPopup() {
        const popupHTML = `
            <div id="exit-intent-popup" class="modal fade" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Wait! Don't Leave Yet! 👟</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-gift fa-3x text-primary mb-3"></i>
                                <h4>Get 10% OFF Your First Order!</h4>
                                <p class="text-muted">Join thousands of happy customers and get exclusive deals on premium footwear.</p>
                            </div>
                            <div class="mb-3">
                                <input type="email" id="popup-email" class="form-control" placeholder="Enter your email">
                            </div>
                            <button id="claim-discount" class="btn btn-primary btn-lg w-100">Claim My Discount</button>
                            <p class="small text-muted mt-2">*Valid for first-time customers only</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', popupHTML);
        
        document.getElementById('claim-discount').addEventListener('click', () => this.claimDiscount());
    }
    
    showPopup() {
        const modal = new bootstrap.Modal(document.getElementById('exit-intent-popup'));
        modal.show();
        this.shown = true;
        sessionStorage.setItem('exitPopupShown', 'true');
    }
    
    async claimDiscount() {
        const email = document.getElementById('popup-email').value;
        if (!email || !email.includes('@')) {
            alert('Please enter a valid email address');
            return;
        }
        
        try {
            const response = await fetch('/api/newsletter-signup.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, source: 'exit_intent' })
            });
            
            const data = await response.json();
            if (data.success) {
                alert('Discount code sent to your email! Check your inbox.');
                bootstrap.Modal.getInstance(document.getElementById('exit-intent-popup')).hide();
            }
        } catch (error) {
            alert('Something went wrong. Please try again.');
        }
    }
}

// Initialize exit intent popup
document.addEventListener('DOMContentLoaded', () => {
    new ExitIntentPopup();
});