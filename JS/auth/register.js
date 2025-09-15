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
    const codeInput = document.getElementById('verification_code_input');
    const digits = (codeInput.value || '').replace(/\D/g, '').slice(0, 6);

    const msg = document.getElementById('mensajeCodigo');

    if (digits.length !== 6) {
        msg.style.color = "red";
        msg.textContent = "Debes ingresar los 6 dígitos.";
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'verify');
        formData.append('user_email', correo);
        formData.append('verification_code', digits);

        const response = await fetch('../Controller/user_management/UserController.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (!result.ok) {
            msg.style.color = "red";
            msg.textContent = result.error || "Código inválido";
        } else {
            msg.style.color = "green";
            msg.textContent = "✅ Usuario verificado con éxito";

            setTimeout(() => {
                hideModal('modalCodigo');
                hideModal('modalRegister');
                // Opcional: mostrar login
                // showModal('modalLogin');
            }, 2000);
        }
    } catch (error) {
        console.error(error);
        msg.style.color = "red";
        msg.textContent = "Error de conexión con el servidor.";
    }
}
