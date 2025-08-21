
document.getElementById('registerForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    const response = await fetch('../routes/register.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    if (result.error) {
        mostrarModalAlerta(result.error);
    } else if (result.success) {
        mostrarModalVerificacion(result.message);
    }
});
