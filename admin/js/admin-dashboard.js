document.addEventListener('DOMContentLoaded', function() {
    // Fetch dashboard stats
    fetchDashboardStats();
    
    // Fetch recent auctions
    fetchRecentAuctions();
    
    // Fetch recent bids
    fetchRecentBids();
});

// Fetch dashboard statistics
function fetchDashboardStats() {
    fetch('../api/admin/get_dashboard_stats.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('total-products').textContent = data.data.total_products;
                document.getElementById('active-auctions').textContent = data.data.active_auctions;
                document.getElementById('total-bids').textContent = data.data.total_bids;
                document.getElementById('total-users').textContent = data.data.total_users;
            } else {
                showError(data.error);
            }
        })
        .catch(error => {
            console.error('Error fetching dashboard stats:', error);
            showError('Failed to load dashboard statistics');
        });
}

// Fetch recent auctions
function fetchRecentAuctions() {
    fetch('../api/admin/get_recent_auctions.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                renderRecentAuctions(data.data);
            } else {
                showError(data.error);
            }
        })
        .catch(error => {
            console.error('Error fetching recent auctions:', error);
            showError('Failed to load recent auctions');
        });
}

// Render recent auctions table
function renderRecentAuctions(auctions) {
    const tableBody = document.getElementById('recent-auctions');
    
    if (auctions.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">No auctions found</td></tr>';
        return;
    }
    
    let html = '';
    auctions.forEach(auction => {
        html += `
            <tr>
                <td>${auction.id}</td>
                <td>${auction.product_name}</td>
                <td>$${parseFloat(auction.start_price).toFixed(2)}</td>
                <td>$${parseFloat(auction.current_bid).toFixed(2)}</td>
                <td>${auction.end_date}</td>
                <td>
                    <span class="status-badge status-${auction.status}">
                        ${auction.status.charAt(0).toUpperCase() + auction.status.slice(1)}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view-btn" onclick="viewAuction(${auction.id})">View</button>
                        ${auction.status === 'active' ? 
                            `<button class="action-btn end-btn" onclick="endAuction(${auction.id})">End</button>` : ''}
                    </div>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
}

// Fetch recent bids
function fetchRecentBids() {
    fetch('../api/admin/get_recent_bids.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                renderRecentBids(data.data);
            } else {
                showError(data.error);
            }
        })
        .catch(error => {
            console.error('Error fetching recent bids:', error);
            showError('Failed to load recent bids');
        });
}

// Render recent bids table
function renderRecentBids(bids) {
    const tableBody = document.getElementById('recent-bids');
    
    if (bids.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No bids found</td></tr>';
        return;
    }
    
    let html = '';
    bids.forEach(bid => {
        html += `
            <tr>
                <td>${bid.id}</td>
                <td>${bid.username}</td>
                <td>${bid.product_name}</td>
                <td>$${parseFloat(bid.amount).toFixed(2)}</td>
                <td>${bid.created_at}</td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
}

// View auction details
function viewAuction(auctionId) {
    window.location.href = `auctions.php?id=${auctionId}`;
}

// End an auction
function endAuction(auctionId) {
    if (confirm('Are you sure you want to end this auction?')) {
        fetch('../api/admin/end_auction.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `auction_id=${auctionId}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Auction ended successfully');
                fetchRecentAuctions(); // Refresh the auctions list
            } else {
                showError(data.error);
            }
        })
        .catch(error => {
            console.error('Error ending auction:', error);
            showError('Failed to end auction');
        });
    }
}

// Show error message
function showError(message) {
    alert(message);
}