// jQuery no-conflict wrapper
(function($){

  // On document ready
  $(function() {

    $( "#member-nos-tabs" ).tabs();

    $('#available-nos .use-member-no-link').bind('click', window.cmmp_user_number_click);

  });

  window.cmmp_user_number_click = function(e) {
    e.preventDefault();

    $(this).fadeOut();
    var membership_no = $(this).closest('li').attr('id').replace(/^available-no-/, '');
    
    $.ajax({
      url: ajaxurl,
      type: 'POST',
      data: {
        action: 'use_membership_no',
        membership_no: membership_no
      },
      dataType: 'json',
      success: CMMP.use_number_click_ajax_success,
      error: CMMP.use_number_click_ajax_error
    });
  }

  window.CMMP = {
    use_number_click_ajax_success: function(data, textStatus, xhr)
    {
      if (!data.success)
      {
        console.error('Unable to use number.');
        if (!!data.error.message)
          console.error(data.error.message);
        return;
      }
      var membership_no = data.membership_no;
      var item_id = '#available-no-' + membership_no;
      $(item_id).fadeOut(function()
      {
        var no = item_id.replace('#available-no-', '');
        var next_sibling = $('#used-nos ul li').last();
        $('#used-nos ul li').each(function(i, el) {
console.log('Comparing ' + $(el).attr('id').replace('used-no-', '') + ' with ' + no);
          if ($(el).attr('id').replace('used-no-', '') > no)
          {
            next_sibling = el;
            return false;
          }
        });
        $(this).attr('id', $(this).attr('id').replace('available-no-', 'used-no-')).insertBefore($(next_sibling)).show().find('a').remove();
        // Now sort the $('#used-nos ul')

      });
    },
    use_number_click_ajax_error: function(jqXHR, textStatus, errorThrown)
    {
      console.log(jqXHR);
      alert('There was a problem (' + textStatus + ').  Please try again.');
    }
  };

})(jQuery);