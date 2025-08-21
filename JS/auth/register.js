document.getElementById('registerForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append("action", "register"); // 🚀 indica qué hacer

    try {
        const response = await fetch('../Controller/user_management/UserController.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json(); // <-- ya debería recibir JSON válido

        if (!result.ok) {
            mostrarModalAlerta(result.error || "Error desconocido en registro");
        } else {
            mostrarModalVerificacion(result.email);
            showModal('modalCodigo');
            document.getElementById('correo_verificar').value = result.email;
        }
    } catch (error) {
        mostrarModalAlerta("Ocurrió un error en la conexión.");
        console.error(error);
    }
});
