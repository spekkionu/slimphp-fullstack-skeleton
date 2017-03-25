@extends('layout.app')

@section('wrapper')
    <header class="site__header site__header--home">
        <div class="site__header__content">
            <div class="container">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin finibus semper. Nullam
                    elementum nulla placerat velit laoreet rutrum. Donec laoreet euismod mauris, et imperdiet turpis
                    aliquet in. Mauris efficitur nibh id dignissim aliquet.
                </p>
                <p>
                    <button type="button" class="btn btn-primary">A Button</button>
                </p>
            </div>

        </div>
    </header>
    <div class="site__content">
        <div class="container">
            @include('partials.flash-messages')

            <h1 class="page-header">Home Page</h1>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin finibus semper. Nullam
                elementum nulla placerat velit laoreet rutrum. Donec laoreet euismod mauris, et imperdiet turpis aliquet
                in. Mauris efficitur nibh id dignissim aliquet. Nam eu ligula eu leo rhoncus fringilla accumsan a diam.
                Sed imperdiet augue quis leo efficitur pretium. Etiam bibendum urna eu varius congue. Vestibulum
                interdum fringilla pharetra. Aliquam convallis lectus risus, malesuada pretium lorem tristique vel.
                Quisque consectetur urna et ligula sodales, ac imperdiet dolor iaculis. Maecenas in dignissim velit,
                eget porttitor tortor. Proin vulputate rhoncus massa. Quisque tincidunt vehicula diam, id convallis enim
                auctor fermentum. Suspendisse potenti.
            </p>
            <p>
                Nunc nec pretium ex. Donec urna orci, aliquam at lacinia in, egestas id dui. Nulla pulvinar nunc id nunc
                porttitor, at euismod ligula tempus. Pellentesque porta, arcu non suscipit pretium, nisl ligula posuere
                ante, in finibus quam leo vitae risus. Vivamus at metus tortor. Integer risus tellus, viverra at augue
                ut, congue malesuada tellus. Duis erat erat, dignissim nec augue eu, cursus imperdiet erat. Nam
                fringilla et sem non fringilla.
            </p>
            <p>
                Maecenas sollicitudin in nisi eget lacinia. Donec aliquam convallis mi facilisis commodo. Sed ut nibh
                lobortis, pretium est vel, bibendum lectus. Vivamus imperdiet vehicula dolor, quis tempor tortor finibus
                volutpat. Vivamus non libero eu ante fermentum suscipit at nec erat. Suspendisse sagittis volutpat arcu,
                id mollis felis fermentum et. Quisque eget magna malesuada, aliquam sem non, convallis odio. Phasellus
                scelerisque lacus et tellus elementum congue. Praesent iaculis est viverra, condimentum turpis in,
                commodo ipsum. Etiam luctus sagittis feugiat. Donec ac augue ultrices ex laoreet elementum. Phasellus
                massa nisl, cursus pretium facilisis quis, convallis eu est. Integer eget accumsan lacus. Nullam nec
                rutrum est.
            </p>
            <p>
                Fusce blandit id elit egestas dapibus. In pretium orci eu risus vulputate finibus. Suspendisse ac
                volutpat quam. Nullam cursus, enim in semper varius, turpis nisl commodo nulla, ac auctor ante lacus nec
                justo. Integer efficitur volutpat risus, quis vulputate purus auctor vel. Quisque arcu lacus, ultricies
                eu ex ut, imperdiet sagittis ex. Mauris pulvinar libero eros, ac convallis ex sollicitudin id.
            </p>
            <p>
                Donec nec ligula eget mauris dictum rhoncus feugiat sit amet mauris. Suspendisse potenti. Aenean nec
                lectus nec nibh dapibus tristique. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam
                bibendum tellus viverra est lacinia consectetur. Donec mattis eros eget nisi congue, non gravida mi
                convallis. Phasellus at felis facilisis, fermentum ligula in, lacinia turpis. Quisque dictum venenatis
                enim, sit amet vestibulum ex ornare nec. Pellentesque fringilla, quam non luctus rhoncus, nulla lorem
                sodales arcu, sed hendrerit libero nisi non lorem. Aenean interdum leo eget felis placerat venenatis.
                Vestibulum tincidunt elementum massa. Integer
                ligula lectus, egestas dapibus eros nec, pulvinar cursus ante. Integer a ex euismod, condimentum neque
                in, porta
                ex. Donec quis sapien eget arcu sagittis posuere. Cras a vehicula nunc.
            </p>

        </div>
    </div>

@endsection
