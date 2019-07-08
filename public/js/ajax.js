jQuery(document).ready(function ()
{
    const id = $('#id').attr('data-id');
    const views = {
        url: 'http://symfony/ajax_request/' + id
    };
    nbViews = $.ajax(views).done(parse);
    console.log(nbViews);

});

function parse(json) {
    // Afficher la nouvelle valeur du nombre de likes
    console.log('Retour de l\'appel AJAX');
    $('#id').text(json.views);
}