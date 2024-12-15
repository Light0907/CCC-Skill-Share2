<div class="custom-modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel">
    <div class="custom-modal-dialog">
        <div class="custom-modal-content">
            <div class="custom-modal-header bg-success">
                <h5 class="custom-modal-title text-white" id="successModalLabel">Success</h5>
            </div>
            <div class="custom-modal-body">
                <p id="successMessage">Your operation was successful.</p>
            </div>
        </div>
    </div>
</div>

<div class="custom-modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel">
    <div class="custom-modal-dialog">
        <div class="custom-modal-content">
            <div class="custom-modal-header bg-danger">
                <h5 class="custom-modal-title text-white" id="errorModalLabel">Error</h5>
            </div>
            <div class="custom-modal-body">
                <p id="errorMessage">There was an error processing your request.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showSuccessModal(message) {
        if (message) {
            document.getElementById('successMessage').textContent = message;
        }
        const modal = document.getElementById('successModal');
        modal.addEventListener('click', () => {
            modal.classList.remove('show');
            modal.hide();
        });
        modal.classList.add('show');

        setTimeout(function () {
            modal.classList.remove('show');
            modal.hide();
        }, 2000);
    }

    function showErrorModal(message) {
        if (message) {
            document.getElementById('errorMessage').textContent = message;
        }

        const modal = document.getElementById('errorModal');
        modal.addEventListener('click', () => {
            modal.classList.remove('show');
            modal.hide();
        });
        modal.classList.add('show');

        setTimeout(function () {
            modal.classList.remove('show');
            modal.hide();
        }, 2000);
    }
</script>