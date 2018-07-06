$(function() {
    marked.setOptions({
        langPrefix: ''
    });
    if($('#editor').val() != ''){
	    var src = $('#editor').val();
	    var html = marked(src);
        $('#result').html(html);
        $('#hidden_result').val(html);
    }
    $('#editor').keyup(function() {
        var src = $(this).val();
        var html = marked(src);
        $('#result').html(html);
        $('#hidden_result').val(html);
    });
});