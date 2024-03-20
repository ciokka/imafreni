jQuery(function ($) {
  $(document).ready(function () {
      var menuItems = $('#wp-bottom-menu').find('span');

      menuItems.each(function () {
        var menuText = $(this).html();

        if (menuText.indexOf('Cerca') !== -1) {
          if (location.href.indexOf('/en/') !== -1) {
            menuText = menuText.replaceAll('Cerca', 'Search');
            $('input[placeholder="Cerca"]').attr('placeholder', 'Search');
          }
          if (location.href.indexOf('/es/') !== -1) {
            menuText = menuText.replaceAll('Cerca', 'Search in Spanish');
            $('input[placeholder="Cerca"]').attr('placeholder', 'Search in Spanish');
          }
          if (location.href.indexOf('/de/') !== -1) {
            menuText = menuText.replaceAll('Cerca', 'Search in German');
            $('input[placeholder="Cerca"]').attr('placeholder', 'Search in German');
          }
          if (location.href.indexOf('/fr/') !== -1) {
            menuText = menuText.replaceAll('Cerca', 'Search in French');
            $('input[placeholder="Cerca"]').attr('placeholder', 'Search in French');
          }
          $(this).html(menuText);
        }

        if (menuText.indexOf('Contatti') !== -1) {
          if (location.href.indexOf('/en/') !== -1) {  
            menuText = menuText.replaceAll('Contatti', 'Contacts');
            $('a[href="https://imafreni.it/contatti/"]').attr('href', 'https://imafreni.it/en/contacts');
          }
          if (location.href.indexOf('/es/') !== -1) {
            menuText = menuText.replaceAll('Contatti', 'Contacts in Spanish');
            $('a[href="https://imafreni.it/contatti/"]').attr('href', 'https://imafreni.it/es/contacts');
          }
          if (location.href.indexOf('/de/') !== -1) {
            menuText = menuText.replaceAll('Contatti', 'Contacts in German');
            $('a[href="https://imafreni.it/contatti/"]').attr('href', 'https://imafreni.it/de/contacts');
          }
          if (location.href.indexOf('/fr/') !== -1) {
            menuText = menuText.replaceAll('Contatti', 'Contacts in French');
            $('a[href="https://imafreni.it/contatti/"]').attr('href', 'https://imafreni.it/fr/contacts');
          }
          $(this).html(menuText);
        }
      });

      if (window.innerWidth <= 1024) {
      
      $('.sub-menu').hide();

      $('a[href="#34"]').append('<span class="toggle-icon"> <i class="fas fa-chevron-down"></i></span>');
      
      $('a[href="#34"]').click(function(e) {
        e.preventDefault();
        
        $(this).siblings('.sub-menu').slideToggle();
      });
    }
      

      // var $element = $('[data-testid="widgetButtonFrame"]');
  
      // // Ottieni le coordinate X e Y dell'elemento rispetto al documento
      // var offset = $element.offset();
      // var x = offset.left;
      // var y = offset.top;
      
      // // Stampare le coordinate X e Y nella console del browser
      // console.log("Coordinate X:", x);
      // console.log("Coordinate Y:", y);
      
      // // document.getElementById('widgetButtonFrame').contentWindow.document

    function closeChat() {
      $("#smartsupp-widget-container").css({
        opacity: 0,
        zIndex: 1
      });
    }

    function openChat() {
      var iframe = $("#smartsupp-widget-container").find('iframe')[0];
      $(iframe.contentWindow.document).find(".bg-primary-gradient.bg-primary-gradient-hover").trigger('click');

      window.setTimeout(function() {

        var iframe = $("[data-testid=\"widgetMessengerFrame\"]").find('iframe')[0];
        //  console.log($(iframe.contentWindow.document).find("svg").parent()[1]);
        $($(iframe.contentWindow.document).find("svg").parent()[1]).on("click", function(){
          closeChat();
        });
      }, 1000);

      /*
      $(iframe.contentWindow.document).find("button.btn").parent().on("click", function(e) {
        closeChat();
      });*/
      window.setTimeout(function() {
      $("#smartsupp-widget-container").css({
        opacity: 1,
        zIndex: 99999
      })}, 200);

    }



    $(document).on("click", "[href=\"#chat\"]", () => openChat()); 

  });
});



