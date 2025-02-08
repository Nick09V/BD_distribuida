document.addEventListener("DOMContentLoaded", function () {
    const botonMaster = document.getElementById("buttonNorte");
    const botonRemoto = document.getElementById("buttonSur");

    if (botonMaster) {
        botonMaster.addEventListener("click", function () {
            const sucursal = 'norte';

            fetch("../php/login.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ sucursal })
                
            })
            
            .catch(error => console.error("Error en la solicitud de login:", error));
            console.log("Botón master clickeado");
            window.location.href = "../html/productosNorte.html";
            
        });
    }

    if (botonRemoto) {
        botonRemoto.addEventListener("click", function () {
                const sucursal = 'sur';
    
                fetch("../php/login.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ sucursal })
                    
                })
                
                .catch(error => console.error("Error en la solicitud de login:", error));
                console.log("Botón master clickeado");
                window.location.href = "../html/productosSur.html";
        });
    }

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
            });
        });
    }
    

    function cargarProductos() {
        fetch("../php/productos.php")
            .then(response => response.json())
            .then(data => {
                let tabla = document.getElementById("productosTabla");
                tabla.innerHTML = ""; // Limpiar tabla antes de agregar nuevos datos
    
                data.forEach(producto => {
                    let fila = `
                        <tr>
                            <td>${producto.ID_PRODUCTO}</td>
                            <td>${producto.NOMBREPRODUCTO}</td>
                            <td>${producto.PRECIO}</td>
                        </tr>`;
                    tabla.innerHTML += fila;
                });
            })
            .catch(error => console.error("Error al cargar productos:", error));
    }
    
    // Cargar productos automáticamente cuando se abre la página
    document.addEventListener("DOMContentLoaded", cargarProductos);
    
});
