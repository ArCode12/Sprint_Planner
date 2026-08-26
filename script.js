fetch("get_cards.php")
    .then(response => response.json())
    .then(data => console.log(data)); 