<!-- Payment Proof Upload Modal -->
<div class="modal fade" id="paymentProofModal" tabindex="-1" aria-labelledby="paymentProofModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentProofModalLabel">Upload Payment Proof</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="payment-proof-form" enctype="multipart/form-data">
          <input type="hidden" id="order-id-input" name="order_id">
          
          <div class="mb-3">
            <p>Please upload a screenshot or photo of your payment receipt.</p>
            <p class="text-muted small">Accepted formats: JPG, PNG, PDF</p>
          </div>
          
          <div class="mb-3">
            <label for="proof_image" class="form-label">Payment Proof</label>
            <input type="file" class="form-control" id="proof_image" name="proof_image" accept="image/jpeg,image/png,application/pdf" required>
          </div>
          
          <div class="mb-3">
            <div id="image-preview" class="text-center d-none">
              <p class="text-muted">Preview:</p>
              <img id="preview-image" src="#" alt="Preview" class="img-fluid mb-2 rounded" style="max-height: 200px;">
            </div>
          </div>
          
          <button type="submit" class="btn-primary w-100">Upload Payment Proof</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // Image preview functionality
  document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('proof_image');
    const imagePreview = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-image');
    
    if (fileInput) {
      fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
          const file = this.files[0];
          
          // Only show preview for images, not PDFs
          if (file.type.match('image.*')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
              previewImage.src = e.target.result;
              imagePreview.classList.remove('d-none');
            }
            
            reader.readAsDataURL(file);
          } else {
            // Hide preview for non-image files
            imagePreview.classList.add('d-none');
          }
        }
      });
    }
  });
</script>