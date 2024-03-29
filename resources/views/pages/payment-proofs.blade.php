@include('includes.header-before-login')
 <div class="container">
    <h4 class="text-center mt-4 mb-4">Latest 100 payouts history</h4>
    <div class="table-responsive">
        <table class="table no-wrap">
          <thead>
            <tr>
              <th class="border-top-0">Username</th>
              <th class="border-top-0">Amount</th>
              <th class="border-top-0">Method</th>
              <th class="border-top-0">Date</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($withdrawals as $withdrawal)
              <tr>
                <td class="txt-oflo">
                  <span class="fi fi-us"></span> {{ $withdrawal->user->username }}
                </td>
                <td>
                  <span class="text-success">${{ $withdrawal->amount_after_fee }}</span>
                </td>
                <td>
                  <span class="text-info">
                    <img style="width: 100px; height:25px;" src="{{ $withdrawal->image }}" >
                  </span>
                </td>
                <td>
                  <span class="text-dark">{{ $withdrawal->updated_at->diffForHumans() }}</span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
 </div>
@include('includes.footer-before-login')