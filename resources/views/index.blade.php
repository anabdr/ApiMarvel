<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Superhéroes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1>Listado de Superhéroes</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Detalles</th>
            </tr>
        </thead>
        <tbody id="heroes-table">
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('heroes-table');

    fetch('http://localhost/apiMarvel/public/api/getCharacters')
        .then(response => response.json())
        .then(data => {
            data.forEach(character => {
                const tr = document.createElement('tr');
                
                const imageUrl = character.image 
                    ? character.image : character.thumbnail.path + '.' + character.thumbnail.extension;
                    
                
                tr.innerHTML = `
                    <td><img src="${imageUrl}" alt="${character.name}" style="width: 100px; height: 100px;"></td>
                    <td>
                        <strong>${character.name}</strong><br>
                        ${character.description || ''}
                    </td>
                `;
                
                tableBody.appendChild(tr);
            });
        })
        .catch(error => console.error('Error al obtener los personajes:', error));
});
</script>

</body>
</html>
