document.addEventListener("DOMContentLoaded", function () {
    varUbicacion = "norte";

    // Manejo del formulario de pedidos
    const pedidoForm = document.getElementById("pedidoForm");
    if (pedidoForm) {
        pedidoForm.addEventListener("submit", function (event) {
            event.preventDefault();
            const numPedido = document.getElementById("numPedido").value;
            const fechaPedido = document.getElementById("fechaPedido").value;
            const cantidad = document.getElementById("cantidad").value;
            const productoId = document.getElementById("productoId").value;

            fetch("../php/pedido.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ numPedido, fechaPedido, cantidad, productoId })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("pedidoMsg").innerText = data.message;
            })
            .catch(error => console.error("Error al enviar pedido:", error));
        });
    }
});

// Función para cargar productos
function cargarProductos() {
    fetch("../php/productos.php")
        .then(response => response.json())
        .then(data => {
            let tabla = document.getElementById("productosTabla");
            if (!tabla) return;

            tabla.innerHTML = ""; // Limpiar tabla antes de agregar nuevos datos

            data.forEach(producto => {
                let fila = `
                    <tr>
                        <td>${producto.ID_PRODUCTO}</td>
                        <td>${producto.NOMBRE_PRODUCTO}</td>
                        <td>${producto.PRECIO}</td>
                    </tr>`;
                tabla.innerHTML += fila;
            });
        })
        .catch(error => console.error("Error al cargar productos:", error));
}



// Función para seleccionar ubicación
function seleccionarUbicacion(ubicacion) {
    fetch("../php/verificarUbicacion.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ubicacion })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (ubicacion === "norte") {
                varUbicacion = "norte";
                window.location.href = "productosNorte.html";
            } else {
                varUbicacion = "sur";
                window.location.href = "productosSur.html";
            }
        } else {
            console.log("IP del usuario:", data.ipUsuario);
            alert("No tienes acceso desde esta ubicación.");
        }
    })
    .catch(error => console.error("Error en la verificación de ubicación:", error));
}

// Función para cargar datos (corregida)
function cargarDatos(tabla) {
    console.log(tabla);
    if (!tabla) {
        console.error("Error: tabla no definida.");
        return;
    }

    fetch("../php/cargarDatos.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ tabla })
    })
    .then(response => response.text())  // <-- Leer como texto en vez de JSON
    .then(text => {
        console.log("Respuesta del servidor:", text);  // <-- Mostrar la respuesta en consola
        try {
            let data = JSON.parse(text);  // <-- Intentar convertir a JSON
            if (!Array.isArray(data) || data.length === 0) {
                console.error("Datos vacíos o en formato incorrecto:", data);
                document.getElementById("tablaResultados").innerHTML = "<p>No hay datos disponibles.</p>";
                return;
            }

            let tablaResultados = document.getElementById("tablaResultados");
            if (!tablaResultados) return;

            let html = "<table border='1'><tr>";
            Object.keys(data[0]).forEach(key => {
                html += `<th>${key.toLowerCase()}</th>`; // Convertir a minúsculas si es necesario
            });
            html += "</tr>";

            data.forEach(row => {
                html += "<tr>";
                Object.values(row).forEach(value => {
                    html += `<td>${value}</td>`;
                });
                html += "</tr>";
            });

            html += "</table>";
            tablaResultados.innerHTML = html;
        } catch (error) {
            console.error("Error al convertir JSON:", error, "Respuesta recibida:", text);
        }
    })
    .catch(error => console.error("Error al cargar datos3:", error));
}







function mostrarFormulario() {
    let seleccion = document.getElementById("tabla").value;
    let formularios = document.querySelectorAll(".formulario");

    formularios.forEach(form => {
        form.style.display = "none"; // Oculta todos los formularios
    });

    if (seleccion) {
        document.getElementById(`form-${seleccion}`).style.display = "block"; // Muestra solo el seleccionado
    }
}







function insertarDatos(tabla) {
    let url = `../php/insert/${tabla}.php`;
    let bodyData = "";

    if (tabla === "cliente") {
        let id_cliente = parseInt(document.getElementById("id_cliente").value);
        let id_sucursal = parseInt(document.getElementById("id_sucursal_cliente").value);
        let nombre_cli = document.getElementById("nombre_cli").value.trim();
        let apellido_cli = document.getElementById("apellido_cli").value.trim();
        let cedula = document.getElementById("cedula").value.trim();
        let correo = document.getElementById("correo").value.trim();

        bodyData = `id_cliente=${id_cliente}&id_sucursal=${id_sucursal}&nombre_cli=${nombre_cli}&apellido_cli=${apellido_cli}&cedula=${cedula}&correo=${correo}&varUbicacion=${varUbicacion}`;
        console.log(varUbicacion)
    } 
    else if (tabla === "empleado") {
        let id_empleado = parseInt(document.getElementById("id_empleado").value);
        let id_sucursal = parseInt(document.getElementById("id_sucursal_empleado").value);
        let nombre = document.getElementById("nombre").value.trim();
        let direccion = document.getElementById("direccion").value.trim();
        let telefono = document.getElementById("telefono").value.trim();

        bodyData = `id_empleado=${id_empleado}&id_sucursal=${id_sucursal}&nombre=${nombre}&direccion=${direccion}&telefono=${telefono}&varUbicacion=${varUbicacion}`;
    } 
    else if (tabla === "pedido") {
        let id_pedido = parseInt(document.getElementById("id_pedido").value);
        let id_sucursal = parseInt(document.getElementById("id_sucursal_pedido").value);
        let id_cliente = parseInt(document.getElementById("id_cliente_pedido").value);
        let id_empleado = parseInt(document.getElementById("id_empleado_pedido").value);
        let id_producto = parseInt(document.getElementById("id_producto_pedido").value);
        let num_pedido = parseInt(document.getElementById("num_pedido").value);
        let fecha_pedido = document.getElementById("fecha_pedido").value;
        let cantidad = parseFloat(document.getElementById("cantidad").value);

        bodyData = `id_pedido=${id_pedido}&id_sucursal=${id_sucursal}&id_cliente=${id_cliente}&id_empleado=${id_empleado}&id_producto=${id_producto}&num_pedido=${num_pedido}&fecha_pedido=${fecha_pedido}&cantidad=${cantidad}&varUbicacion=${varUbicacion}`;
    }
    
    else if (tabla === "producto") {
        let id_producto = parseInt(document.getElementById("id_producto").value);
        let nombre_producto = document.getElementById("nombre_producto").value.trim();
        let precio = document.getElementById("precio").value.trim();

        bodyData = `id_producto=${id_producto}&nombre_producto=${nombre_producto}&precio=${precio}`;
        console.log("Datos del producto:", { id_producto, nombre_producto, precio });
    } 
    else if (tabla === "inventario") {
        let id_inventario = parseInt(document.getElementById("id_inventario").value);
        let id_producto = parseInt(document.getElementById("id_producto_inventario").value);
        let id_sucursal = parseInt(document.getElementById("id_sucursal_inventario").value);
        let cantidad = parseInt(document.getElementById("cantidad_inventario").value);

        bodyData = `id_inventario=${id_inventario}&id_producto=${id_producto}&id_sucursal=${id_sucursal}&cantidad=${cantidad}`;
    } 
    else if (tabla === "sucursal") {
        let id_sucursal = parseInt(document.getElementById("id_sucursal").value);
        let direccion = document.getElementById("direccion_sucursal").value.trim();
        let telefono = document.getElementById("telefono_sucursal").value.trim();

        bodyData = `id_sucursal=${id_sucursal}&direccion=${direccion}&telefono=${telefono}`;
    }

    // Hacer la solicitud fetch a PHP según la tabla seleccionada
    fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: bodyData
    })
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta del servidor:", data);
        document.getElementById("mensaje").innerText = data.success 
            ? "Datos insertados correctamente" 
            : `Error: ${data.error}`;
    })
    .catch(error => console.error("Error al insertar datos:", error));
}






function eliminarRegistro() {
    let id = document.getElementById("id_input").value;
    let tabla = document.getElementById("tabla_select").value;
    
    if (!id) {
        document.getElementById("mensaje").innerText = "Por favor, ingrese un ID.";
        return;
    }

    let url = `../php/eliminar/eliminar_${tabla}.php`;
    let bodyData = "";

    if (tabla === "cliente") {
        bodyData = `id_cliente=${id}&varUbicacion=${varUbicacion}`;
    } else if (tabla === "empleado") {
        bodyData = `id_empleado=${id}&varUbicacion=${varUbicacion}`;
    } else if (tabla === "pedido") {
        bodyData = `id_pedido=${id}&varUbicacion=${varUbicacion}`;
    } else if (tabla === "producto") {
        bodyData = `id_producto=${id}`;
    } else if (tabla === "inventario") {
        bodyData = `id_inventario=${id}`;
    } else if (tabla === "sucursal") {
        bodyData = `id_sucursal=${id}`;
    }

    fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: bodyData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("mensaje").innerText = data.success 
            ? "Registro eliminado correctamente" 
            : `Error: ${data.error}`;
    })
    .catch(error => {
        console.error("Error:", error);
        document.getElementById("mensaje").innerText = "Hubo un error al eliminar el registro.";
    });
}

