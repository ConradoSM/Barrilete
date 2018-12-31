<div id="Article_Form">
<h1>Cargar encuesta</h1>
    <form method="post" enctype="multipart/form-data" id="createArticle" action="{{ route('createPoll') }}">
        <fieldset>
            <legend>Información</legend>
            <p><b>Autor</b>: {{ Auth::user()->name }}</p>
            <p><b>Fecha de publicación</b>: {{ date('Y-m-d h:i') }}</p> 
        </fieldset>
        <fieldset>
            <legend>Título y Copete</legend>
            <div id="errors"></div>
            <input type="text" name="title" value="" placeholder="Título: éste es el principal título del articulo (*)" required />            
            <textarea name="article_desc" placeholder="Copete: puedes incluir el primer párrafo de tu artículo (*)" required></textarea>
            <input type="submit" value="SIGUIENTE >>" id="enviar" />
        </fieldset>
        @csrf
        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
        <input type="hidden" name="date" value="{{ date('Y-m-d h:i') }}" />
        <input type="hidden" name="author" value="{{ Auth::user()->name }}" />
        <input type="hidden" name="section_id" value="{{ $section->id }}" />
    </form>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.filestyle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/formSubmit.js') }}"></script>