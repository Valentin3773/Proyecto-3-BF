<section class="container row mx-auto">
    <h1 class="col-12 text-center mt-3" id="titulo">Contactanos</h1>
    <div class="infocontacto p-xl-4 p-lg-4 p-0 pt-xl-3 pt-lg-3 pt-md-0 pt-sm-0 pt-0 col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

        <article>

            <div class="contenedor mb-2">

                <div id="circulito">
                    
                    <img src="img/iconosvg/location-pin-svgrepo-com.svg" alt="">
                
                </div>
                <div id="contenedor_fondo">
                    <p class="m-0">Gertrud-Kolmar-Str. 14</p>
                </div>

            </div>

            <div class="contenedor mb-2">

                <div id="circulito">

                    <img src="img/iconosvg/cellphone-communication-handphone-svgrepo-com.svg" alt="">

                </div>
                <div id="contenedor_fondo">
                    <p class="m-0">099 132 465</p>
                </div>

            </div>

            <div class="contenedor mb-2">

                <div id="circulito">

                    <img src="img/iconosvg/telephone-receiver-material-svgrepo-com.svg" alt="">

                </div>
                <div id="contenedor_fondo">
                    <p class="m-0">473 5689</p>
                </div>

            </div>

            <div class="contenedor mb-3">

                <div id="circulito">

                    <img src="img/iconosvg/email-8-svgrepo-com.svg" alt="">
    
                </div>

                <div id="contenedor_fondo">
                    <p class="m-0">pirepepetafeli@gmail.com</p>
                </div>
            </div>

        </article>

        <article class="d-flex justify-content-center gap-3">

            <div id="contenedor_fondo_redes">

                <a href="https://www.instagram.com/saluddentalclinica/?hl=es" target="_blank">
                    <img src="img/iconosvg/instagram.svg" alt="Instragram">
                </a>
                
            </div>

            <div id="contenedor_fondo_redes">

                <a href="" target="_blank">
                    <img src="img/iconosvg/facebook.svg" alt="Facebook">
                </a>
                
            </div>

            <div id="contenedor_fondo_redes">

                <a href="" target="_blank">
                    <img src="img/iconosvg/whatsapp.svg" alt="WhatsApp">
                </a>
                
            </div>

        </article>

        <article class="mt-3">

            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d978.1062272928758!2d-57.963996335760264!3d-31.38757117546271!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95addd2af164a16b%3A0x397cf16081f628b0!2sJoyería%20Y%20Optica%20Carcabelos%20central!5e0!3m2!1ses!2suy!4v1715466322409!5m2!1ses!2suy" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        
        </article>

    </div>

    <aside class="p-xl-2 p-lg-2 p-0 pt-3 pb-4 col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

        <form action="backend/formcontactomanager.php" method="POST" id="formemail" class="p-2 d-flex flex-column justify-content-center">
            
            <div class="subtitulocontainer d-flex justify-content-center mb-3">

                <h2 class="text-center" id="subtitulo">Escríbenos</h2>

            </div>
            
            <div id="contenedor_formulario" class="mb-4">
                <div id="titulo_input"><label for="innombre" class="text-end row">Nombre</label></div>
                <div>
                    <input type="text" name="nombre" id="innombre" title="Ingrese su nombre" placeholder="Ingrese su nombre" required autofocus>
                </div>
            </div>

            <div id="contenedor_formulario" class="mb-4">
                <div id="titulo_input"><label for="inemail" class="text-end row">Email</label></div>
                <div>
                    <input type="email" name="email" id="inemail" title="Ingrese su email" placeholder="Ingrese su email" required>
                </div>
            </div>
        
            <div id="contenedor_formulario" class="mb-4">
                <div id="titulo_input"><label for="intelefono" class="text-end row">Teléfono</label></div>
                <div>
                    <input type="tel" name="telefono" id="intelefono" title="Ingrese su número" placeholder="Ingrese su número" required>
                </div>
            </div>
            
            <div id="contenedor_formulario" class="mb-3">
                <div id="titulo_input"><label for="mensaje" class="text-end row">Mensaje</label></div>
                <div>
                    <textarea name="mensaje" id="mensaje" title="Ingrese el mensaje" placeholder="Ingrese el mensaje" required></textarea>
                </div>
            </div>
            
            <input type="text" id="jejeje" name="jejeje" tabindex="-1">

            <div class="submitcontainer">
                <button type="submit" name="enviar" id="enviarmail">
                    <div class="svg-wrapper-1">
                        <div class="svg-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <path fill="none" d="M0 0h24v24H0z"></path>
                                <path fill="currentColor" d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"></path>
                            </svg>
                        </div>
                    </div>
                    <span>Enviar</span>
                </button>
            </div>

        </form>

    </aside>

</section>