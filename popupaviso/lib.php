<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Hook moderno para exibir pop-up no rodapé da página.
 */
function local_popupaviso_before_footer()
{
    global $PAGE;

    // ✅ Verifica se a URL atual é a mesma configurada
    $urlconfig = get_config('local_popupaviso', 'url');
    $urlatual = $PAGE->url->out(false);
    if (empty($urlconfig) || $urlatual !== $urlconfig) {
        return;
    }

    // ✅ Mensagem configurada pelo admin
    $mensagem = get_config('local_popupaviso', 'mensagem');
    if (empty($mensagem)) {
        return;
    }

    // ✅ Cor de fundo configurada
    $cor = get_config('local_popupaviso', 'cor');
    if (empty($cor)) {
        $cor = '#f8d7da'; // fallback padrão
    }

    // ✅ Vídeo do YouTube (se houver)
    $videourl = get_config('local_popupaviso', 'video');
    $videoframe = '';
    if (!empty($videourl)) {
        if (preg_match('/(?:v=|be\/)([a-zA-Z0-9_-]+)/', $videourl, $matches)) {
            $videoid = $matches[1];
            $videoframe = "
                <div style='position: relative; width: 100%; padding-bottom: 56.25%; height: 0;'>
                    <iframe 
                        src='https://www.youtube.com/embed/{$videoid}' 
                        style='position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;' 
                        allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' 
                        allowfullscreen>
                    </iframe>
                </div>
            ";
        }
    }

    // ✅ HTML com CSS inline e controle por sessão + limite
    $limite = (int)get_config('local_popupaviso', 'limite');

    $html = "
        <style>
    #popup-aviso {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background:rgb(239, 239, 239);
    color:rgb(0, 0, 0);
    border: 1px solid rgb(194, 194, 194);
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    z-index: 9999;
    max-width: 90vw;
    max-height: 90vh;
    width: fit-content;
    height: fit-content;
    overflow: auto;
}


    #popup-aviso img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
    }

    #popup-aviso .popup-content {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    #popup-aviso button {
        background-color:rgb(53, 70, 255);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        align-self: flex-end;
    }
</style>


        <div id='popup-aviso' style='display:none'>
            <div class='popup-content'>
                <div>{$mensagem}</div>
                {$videoframe}
                <button id='fechar-popup'>Fechar</button>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const limite = {$limite};
                const chave = 'popupaviso_contador';
                let contador = parseInt(sessionStorage.getItem(chave)) || 0;

                if (limite === 0 || contador < limite) {
                    var popup = document.getElementById('popup-aviso');
                    var fechar = document.getElementById('fechar-popup');
                    popup.style.display = 'block';

                    fechar.addEventListener('click', function () {
                        popup.style.display = 'none';
                        contador++;
                        sessionStorage.setItem(chave, contador);
                    });
                }
            });
        </script>
    ";

    echo $html;
}
