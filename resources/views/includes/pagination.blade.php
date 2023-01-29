<nav class="float-right" style="float:right">
    <ul class="pagination">
        <li class="page-item {{(data_get($data, 'prev_page_url')==null)?'disabled':'' }}">
            <a class="page-link" href="{{data_get($data, 'prev_page_url')}}" aria-label="Previous">
                <i class="bi bi-caret-left-fill"></i>
            </a>
        </li>
        <li class="page-item {{(data_get($data, 'next_page_url')==null)?'disabled':'' }}">
            <a class="page-link" href="{{data_get($data, 'next_page_url')}}" aria-label="Next">
                <i class="bi bi-caret-right-fill"></i>
            </a>
        </li>
    </ul>
</nav>