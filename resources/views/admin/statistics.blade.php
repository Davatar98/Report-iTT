@extends('layouts.form')



@section('content')

<div>

<div class="py-12">
    <!-- Chart's container -->
    <div id="fault_incidences" style="height: 500px"></div>
    <div id="resolve_time_average" style="height: 500px"></div>
    <div id="acknowledgement_time_average" style="height:500px"></div>
    <!-- Charting library -->
    <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
    <!-- Chartisan -->
    <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
    <!-- Your application script -->
    <script>
        const fault_incidences = new Chartisan({
            el: '#fault_incidences',
            url: "@chart('fault_incidences')",
            hooks: new ChartisanHooks()
             .colors(['#4299E1','#FE0045','#C07EF1','#67C560','#ECC94B'])
                .datasets('pie')
                .axis(false)
                .tooltip()
             .title('Reported Faults by Type')
        });
        const resolve_time_average = new Chartisan({
            el: '#resolve_time_average',
            url: "@chart('resolve_time_average')",
            hooks: new ChartisanHooks()
             .colors(['#4299E1','#FE0045','#C07EF1','#67C560','#ECC94B'])
                .datasets('bar')
               .axis(true)
                .tooltip()
             .title('Average Resolve Time Per Fault (Hours)')
             
        });
        const acknowledgement_time_average = new Chartisan({
            el: '#acknowledgement_time_average',
            url: "@chart('acknowledgement_time_average')",
            hooks: new ChartisanHooks()
             .colors(['#4299E1','#FE0045','#C07EF1','#67C560','#ECC94B'])
                .datasets('bar')
              .axis(true)
             .tooltip()
             .title('Average Acknowledgement Time Per Fault(Hours)')
             
        });
    </script>
</div>
</div>


@endsection