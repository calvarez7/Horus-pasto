function descargarFile(data, nombre, tipo = 'text/plain'){

    const blob = new Blob([data], {type: tipo});
    const url = window.URL.createObjectURL(blob);
    const enlace = document.createElement('a');
    
    enlace.download = nombre;
    enlace.href = url;
    document.body.appendChild(enlace);
    enlace.click();
    document.body.removeChild(enlace);
}

export {
    descargarFile
};