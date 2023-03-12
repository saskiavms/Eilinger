<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse flex-grow-1 text-right" id="myNavbar">
        <ul class="navbar-nav ms-auto flex-nowrap">

            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" type="button" class="btn btn-light">Log Out</button>
                    </form>
                  </li>
                </ul>
              </div>
            
        </ul>
    </div>

</nav>