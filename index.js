/**
 * Realiza y procesa la petición asíncrona
 */
async function pedirAnuncios() {
    let url = "controller.php?action=anuncios";

    try {
        let response = await fetch(url);
        let respuesta = await response.json();
        console.log(respuesta.anuncios);

        let resultado = document.getElementById("anuncios");
        resultado.innerHTML = "";

        for (let anuncio of respuesta.anuncios) {

            let anuncioDiv = document.createElement("div");
            anuncioDiv.classList.add('box-anuncio');

            let anuncioImg = document.createElement("img");
            anuncioImg.src = "data:image/jpeg;base64," + anuncio.image;

            
            let anuncioH4 = document.createElement("h4");
            anuncioH4.textContent = anuncio.header;

            let anuncioP = document.createElement("p");
            anuncioP.textContent = anuncio.content;

            anuncioDiv.appendChild(anuncioImg);
            anuncioDiv.appendChild(anuncioH4);
            anuncioDiv.appendChild(anuncioP);
            resultado.appendChild(anuncioDiv);
        }
    } catch (error) {
        console.log("Error:" + error);
    }
}

/**
 * Función principal
 */
function main() { 
    pedirAnuncios();

    setInterval(pedirAnuncios, 5000);
}

// Tras cargarse el DOM, llamar a main()
window.addEventListener('load', main);	
