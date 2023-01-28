<nav class="float-right" style="float:right">
    <ul class="pagination">
        <li class="page-item {{($data->prev_page_url==null)?'disabled':'' }}">
            <a class="page-link" href="{{$data->prev_page_url}}" aria-label="Previous">
                <i class="bi bi-caret-left-fill"></i>
            </a>
        </li>
        @if(!isset($show_page_links) || (isset($show_page_links) && $show_page_links != false))
            @for($i=1;$i<=$data->last_page;$i++)
                @if($i<=($data->current_page+2) && $i>=($data->current_page-2))
                    <li class="page-item {{($i==$data->current_page)? 'active' : ''}}"><a class="page-link"
                                                                                            href="{{$data->path.'page='.$i}}">{{$i}}</a>
                    </li>
                @endif

            @endfor
        @endif
        <li class="page-item {{($data->next_page_url==null)?'disabled':'' }}">
            <a class="page-link" href="{{$data->next_page_url}}" aria-label="Next">
                <i class="bi bi-caret-right-fill"></i>
            </a>
        </li>
    </ul>
</nav>