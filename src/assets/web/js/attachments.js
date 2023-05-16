async function getFileFromUrl(url,namefile = '') {
    const res = await fetch(url);
    const blob = await res.blob();
    const mime = blob.type;
    const ext = mime.slice(mime.lastIndexOf("/") + 1, mime.length);

    const file = new File([blob], namefile+`_filename.${ext}`, {
        type: mime,
    })

    return file;
}