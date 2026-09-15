@extends('layouts.master')

@section('title')
    Organization Chart
@endsection

@section('css')
<style>
    .chart-container {
        width: 100%;
        height: 800px;
        background-color: #ffffff;
    }
</style>
@endsection

@section('content')

<div class="row mb-3 pb-1">
    <div class="col-12">
        <div class="d-flex align-items-lg-center flex-lg-row flex-column justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm flex-shrink-0">
                    <span class="avatar-title bg-light text-dark rounded-circle fs-20 border">
                        <i class="ri-node-tree text-muted"></i>
                    </span>
                </div>
                <div>
                    <h4 class="fs-16 mb-1 fw-bold">Org Chart</h4>
                    <p class="text-muted mb-0 fs-13">View reporting hierarchy for the entire organization.</p>
                </div>
            </div>
            <div class="mt-3 mt-lg-0 d-flex gap-2 align-items-center">
                <div class="input-group input-group-sm border rounded">
                    <input type="text" id="chart-search" class="form-control border-0 bg-light" placeholder="Search Employee" style="width: 150px;">
                    <button class="btn btn-secondary border-0 text-white" type="button" onclick="searchChart()" style="background-color: #878a99;"><i class="ri-refresh-line me-1"></i> Load</button>
                </div>
                <button class="btn btn-sm text-white" style="background-color: #162d50;" onclick="chart.exportPng()"><i class="ri-download-2-line"></i></button>
                <button class="btn btn-success btn-sm px-3 rounded-pill"><i class="ri-question-line align-middle"></i> Read Help</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                @if(count($chartData) > 0)
                    <div class="chart-container" id="chart-container"></div>
                @else
                    <div class="text-center p-5">
                        <i class="ri-node-tree fs-1 display-4 text-light mb-3"></i>
                        <h5 class="text-muted">No employees found to build the chart.</h5>
                        <p class="text-muted">Ensure employees are added and active.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
@if(count($chartData) > 0)
<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/d3-org-chart@3.0.1"></script>
<script src="https://cdn.jsdelivr.net/npm/d3-flextree@2.1.2/build/d3-flextree.js"></script>

<script>
    var chart;
    document.addEventListener("DOMContentLoaded", function() {
        var data = {!! json_encode($chartData) !!};

        // Fix invalid parent IDs and ensure a single root to prevent crashing
        var validIds = data.map(d => d.id);
        var roots = 0;
        data.forEach(d => {
            if (!d.parentId || !validIds.includes(d.parentId)) {
                d.parentId = 'ROOT';
                roots++;
            }
        });

        // Inject the organization root node
        data.push({
            id: 'ROOT',
            parentId: '',
            name: 'Somya HRMS',
            positionName: 'Organization',
            empCode: 'ROOT',
            imageUrl: ''
        });

        chart = new d3.OrgChart()
            .container('.chart-container')
            .data(data)
            .nodeWidth(d => 160)
            .initialZoom(0.8)
            .nodeHeight(d => 70)
            .childrenMargin(d => 40)
            .siblingsMargin(d => 40)
            .compact(false)
            .buttonContent(({ node, state }) => {
                return ''; // Hide default expand/collapse chevron
            })
            .linkUpdate(function(d, i, arr) {
                d3.select(this)
                    .attr("stroke", "#162d50")
                    .attr("stroke-width", 1.5)
                    .attr("stroke-dasharray", "none"); 
            })
            .nodeContent(function(d, i, arr, state) {
                const color = '#162d50';
                const isHighlighted = d.data._highlighted || d.data._upToTheRootHighlighted;
                const borderStyle = isHighlighted ? 'border: 3px solid #0ab39c; box-shadow: 0 0 15px rgba(10, 179, 156, 0.4); transform: scale(1.05);' : 'border: 1px solid #e9ecef;';
                
                return `
                <div style="padding: 0; margin: 0; height: 100%; width: 100%; box-sizing: border-box; font-family: 'Inter', sans-serif;">
                    <div style="
                        background-color: #ffffff;
                        ${borderStyle}
                        border-radius: 4px;
                        overflow: hidden;
                        display: flex;
                        flex-direction: column;
                        height: 100%;
                        transition: all 0.3s ease;
                    ">
                        <div style="background-color: ${color}; color: #ffffff; padding: 4px; font-size: 9px; font-weight: 700; text-align: center; text-transform: uppercase; letter-spacing: 0.5px;">
                            ${d.data.name}
                        </div>
                        <div style="padding: 6px; text-align: center; display: flex; flex-direction: column; justify-content: center; flex-grow: 1;">
                            <div style="font-size: 10px; color: #878a99; font-weight: 600;">${d.data.empCode === 'ROOT' ? '★' : d.data.empCode}</div>
                            <div style="font-size: 10px; color: #212529; font-weight: 700; margin-top: 1px; text-transform: uppercase;">${d.data.positionName}</div>
                        </div>
                    </div>
                </div>
                `;
            })
            .render();
            
        // Expand all initially
        chart.expandAll();
    });

    function searchChart() {
        const query = document.getElementById('chart-search').value.toLowerCase().trim();
        chart.clearHighlighting();
        
        if(!query) {
            chart.render();
            return;
        }
        
        const data = chart.data();
        const matches = data.filter(d => 
            (d.name && d.name.toLowerCase().includes(query)) || 
            (d.empCode && d.empCode.toLowerCase().includes(query)) ||
            (d.positionName && d.positionName.toLowerCase().includes(query))
        );
        
        if(matches.length > 0) {
            matches.forEach(d => {
                chart.setHighlighted(d.id);
                chart.setUpToTheRootHighlighted(d.id);
            });
            chart.render().fit();
            
            // Center on the first match
            setTimeout(() => {
                chart.setCentered(matches[0].id);
            }, 300);
        } else {
            chart.render();
            // Flash red on the input to indicate no results
            const searchInput = document.getElementById('chart-search');
            searchInput.classList.add('is-invalid');
            setTimeout(() => searchInput.classList.remove('is-invalid'), 1000);
        }
    }
    
    // Allow 'Enter' key to trigger search
    document.getElementById('chart-search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchChart();
        }
    });
</script>
@endif
@endsection
