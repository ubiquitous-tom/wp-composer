(function($){
    // Get Carousel to paginate for homepage.
    $('.carousel-pagination-slide').on('slid.bs.carousel', function(carousel) {
        var $relatedTarget = $(carousel.relatedTarget),
            $currentTarget = $(carousel.currentTarget),
            itemNum = $relatedTarget.data('item'),
            content = $currentTarget.data('content'),
            totalPages = $currentTarget.data('total-pages'),
            loadPage = (0 === itemNum%4 || 2==itemNum),
            isLatestItem = ((itemNum+1) === $currentTarget.find('.item').length),
            nextPage =  ($currentTarget.data('page-loaded')+1),
            data = {
                'action': 'paginate',
                'content': content,
                'page': 2,
                'token': atv_object.token
            },
            processData = function(response) {
                var data = (typeof response[0] !== 'undefined' && typeof response[0].options[0] !== 'undefined') ? response[0].options[0] : null,
                    itemByPage = 8,
                    itemsGenerated = itemByPage-1,
                    checkItem = (nextPage*itemByPage - itemsGenerated),
                    pagedItem = $currentTarget.find('.item[data-item='+ checkItem +']'),
                    $carouselListItems = $currentTarget.find('.item');
                if(0 === pagedItem.length && data !== null) {
                    var currentPg = data.currentpage,
                        secondPage = (2 == currentPg),
                        lastPage = (currentPg === totalPages),
                        items = data.media,
                        addNewItems = function(index, value){
                            var $carouselLastItem = $currentTarget.find('.item:last'),
                                $clonedBlock = $carouselLastItem.clone().attr('class', 'item'),
                                dataItem = ($clonedBlock.data('item')*1+1);
                            for(var i=0; i<4; i++) {
                                var imageURL = atv_object.image_url + value.franchiseID + '_avatar?w=400&h=225',
                                    href = atv_object.home_url + '/' + value.franchiseID;
                                if(3 > i) {
                                    var $carouselItem = $carouselLastItem.find('img').eq(i+1);
                                    imageURL = $carouselItem.attr('src');
                                    href = $carouselItem.parent().attr('href');
                                }
                                $clonedBlock.children().eq(i).find('img').attr('src', imageURL);
                                $clonedBlock.children().eq(i).find('a').attr('href', href);
                            }
                            $clonedBlock.attr('data-item', dataItem).data('item', dataItem);
                            $relatedTarget.parent().append($clonedBlock);
                        },
                        completeCarousel = function(items) {
                            var newItems = (typeof items === 'undefined') ? [] : items;
                            for(var i=0; i<3 ; i++) {
                                newItems.push({
                                    'franchiseID': $carouselListItems.eq(i).find('a').attr('href').replace(atv_object.home_url+'/', '')
                                });
                            }
                            return newItems;
                        };

                    if(secondPage) {
                        var itemsLength = 3;
                        if(8>items.length) {
                            items = completeCarousel(items);
                            lastPage = false;
                        }
                        for(var i = 5, j=0; i <= 7 ; i++,j++) {
                            for(var x = 0; x<=j; x++){
                                var $carouselItem = $carouselListItems.eq(i),
                                    $carouselChangeItem = $carouselItem.children().eq(3-x),
                                    imageURL = atv_object.image_url + '{{ID}}_avatar?w=400&h=225',
                                    href = atv_object.home_url + '/{{ID}}',
                                    ID = items[j-x].franchiseID;

                                $carouselChangeItem.find('a').attr('href', href.replace('{{ID}}', ID))
                                                   .find('img').attr('src', imageURL.replace('{{ID}}', ID));

                            }
                        }
                        items.splice(0, itemsLength);
                    }

                    $.each(items, addNewItems);

                    if(lastPage) {
                        var completeItems = completeCarousel()
                        $.each(completeItems , function(index, value){
                            addNewItems(index, value);
                        });
                    }
                    
                    $currentTarget.data('page-loaded', currentPg);
                }
            };
        
        if((loadPage || isLatestItem) && nextPage <= totalPages) {
            data.page = nextPage;
            $.post(atv_object.ajax_url, data, processData).fail(function() {
                console.error('Error getting page number ' + nextPage + ' for ' + content);
            });
        }
    });
    
})(jQuery);
