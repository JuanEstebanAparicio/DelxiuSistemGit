document.getElementById('registerForm').addEventListener('submit', async function (e) {
    e.preventDefault(); // ✋ evitar recarga

    const formData = new FormData(this);

    try {
        const response = await fetch('../routes/register.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.error) {
            mostrarModalAlerta(result.error);
        } else if (result.success) {
            // Pasamos el correo para mostrar en el modal
            mostrarModalVerificacion(result.email);
        }
    } catch (error) {
        mostrarModalAlerta("Ocurrió un error en la conexión.");
        console.error(error);
    }
});

