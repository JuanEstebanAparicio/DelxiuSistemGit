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

async function verificarCodigo() {
    const correo = document.getElementById('correo_verificar').value;

    // Unimos los 6 dígitos escritos en el modal
    const digits = Array.from(document.querySelectorAll('.code-digit'))
                        .map(input => input.value.trim())
                        .join('');

    if (digits.length !== 6) {
        document.getElementById('mensajeCodigo').textContent = "Debes ingresar los 6 dígitos.";
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'verify'); // 🔹 Para que el controlador sepa que es verificación
        formData.append('user_email', correo);
        formData.append('verification_code', digits);

        const response = await fetch('../Controller/user_management/UserController.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (!result.ok) {
            document.getElementById('mensajeCodigo').style.color = "red";
            document.getElementById('mensajeCodigo').textContent = result.error || "Código inválido";
        } else {
            document.getElementById('mensajeCodigo').style.color = "green";
            document.getElementById('mensajeCodigo').textContent = "✅ Usuario verificado con éxito";

            // 🚀 Opcional: cerrar modal después de 2s y mostrar login
            setTimeout(() => {
                hideModal('modalCodigo');
                // Si tienes modal de login, aquí lo podrías abrir:
                // showModal('modalLogin');
            }, 4000);
        }
    } catch (error) {
        console.error(error);
        document.getElementById('mensajeCodigo').style.color = "red";
        document.getElementById('mensajeCodigo').textContent = "Error de conexión con el servidor.";
    }
}
