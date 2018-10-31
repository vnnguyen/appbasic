// DOM element where the Timeline will be attached
  var container = document.getElementById('visualization');
  var data =[];
  $.ajax({
    url: "/appbasic/web/cptour/timeline",
    type: "GET",
    // data: {ncc: ncc},
    // dataType: "json",
    success: function(response){
      //console.log(response); return;
      var str = "";
      for(var i = 0; i < response.length; i++) {
          var obj = response[i];
          str = str+"<a class='name_ncc' data-id='"+obj.id+"'>"+obj.name+"</a>";
          // console.log($str);
      }
    },
    error: function(xhr, ajaxOptions, thrownError) { alert('No response from server'); }
  });
  // Create a DataSet (allows two way data-binding)
  var items = new vis.DataSet([
    
    {id: 2, content: 'item 2', start: '2016-04-14', type: 'point', style: 'color: #666'},
    {id: 4, content: 'cao diem 4', start: '2016/01/01', end: '2016/04/19', type: 'background', className: 'HS', style: 'color: #666', editable: true},
    {id: 5, content: 'thap diem 5', start: '2016/04/20', end: '2016/12/31', type: 'background', className: 'LS', style: 'color: #666', editable: true}
  ]);

  // Configuration for the Timeline
  var options = {
    min: '2010-01-01',
    max: '2030-12-31'
  };

  // Create a Timeline
  var timeline = new vis.Timeline(container, items, options);
  timeline.on('click', function (properties) {
  console.log(timeline);
  alert('selected items: ' + properties.time);
  console.log(timeline.getCurrentTime());
  console.log(timeline.getSelection());
  items.add({id: 1, content: 'item 1', start: '2016-04-20', type: 'point', style: 'color: #666', title: 'khuyen mai dac biet'});
  timeline.setItems(items);
  console.log(items.get());

});
// var now = new Date();
// var start = new Date(now.getFullYear(), 0, 0);
// var diff = now - start;
// var oneDay = 1000 * 60 * 60 * 24;
// var day = Math.floor(diff / oneDay);
// alert(day);