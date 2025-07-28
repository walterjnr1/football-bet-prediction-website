            // Ticket types and prices
            const ticketTypes = [
                { label: "VIP", value: "vip", price: 250 },
                { label: "Regular", value: "regular", price: 100 },
                { label: "Gate Price", value: "gate", price: 120 }
            ];

            // Generate ticket form fields with collapsible logic
            function renderTicketForms(count) {
                let html = '';
                for (let i = 1; i <= count; i++) {
                    html += `
                    <div class="border border-indigo-100 rounded-xl mb-4 bg-indigo-50/30 ticket-collapsed" id="ticket_${i}">
                        <div class="ticket-header" data-ticket-idx="${i}">
                            <span class="font-semibold text-indigo-700">Ticket ${i}</span>
                            <svg class="w-5 h-5 ml-2 transition-transform" id="arrow_${i}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                        <div class="ticket-fields p-4">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="ticket_type_${i}">Ticket Type</label>
                                    <select name="ticket_type_${i}" id="ticket_type_${i}" class="w-full border border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg p-3 transition" required>
                                        ${ticketTypes.map(t => `<option value="${t.value}">${t.label} (GHS ${t.price})</option>`).join('')}
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="fullname_${i}">Full Name</label>
                                    <input name="fullname_${i}" id="fullname_${i}" type="text" placeholder="Full Name" class="w-full border border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg p-3 transition" required />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="email_${i}">Email</label>
                                    <input name="email_${i}" id="email_${i}" type="email" placeholder="you@email.com" class="w-full border border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg p-3 transition" required />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="numphone_${i}">Phone Number</label>
                                    <input name="numphone_${i}" id="numphone_${i}" type="tel" placeholder="Phone Number" class="w-full border border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg p-3 transition" required />
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="address_${i}">Address</label>
                                    <input name="address_${i}" id="address_${i}" type="text" placeholder="Address" class="w-full border border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg p-3 transition" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                }
                document.getElementById('ticketsContainer').innerHTML = html;
                // Expand the first ticket by default
                setTimeout(() => expandTicket(1, count), 10);

                // Add click event listeners for all ticket headers for toggle
                for (let i = 1; i <= count; i++) {
                    const header = document.querySelector(`#ticket_${i} .ticket-header`);
                    if (header) {
                        header.addEventListener('click', function(e) {
                            e.stopPropagation();
                            toggleTicket(i, count);
                        });
                    }
                }
            }

            // Toggle expand/collapse for a ticket form
            function toggleTicket(idx, total) {
                const ticketDiv = document.getElementById(`ticket_${idx}`);
                const arrow = document.getElementById(`arrow_${idx}`);
                const isCollapsed = ticketDiv.classList.contains('ticket-collapsed');
                if (isCollapsed) {
                    // Collapse all others, expand this one
                    for (let i = 1; i <= total; i++) {
                        const tDiv = document.getElementById(`ticket_${i}`);
                        const tArrow = document.getElementById(`arrow_${i}`);
                        if (i === idx) {
                            tDiv.classList.remove('ticket-collapsed');
                            tDiv.classList.add('ticket-expanded');
                            if (tArrow) tArrow.style.transform = "rotate(180deg)";
                            const fields = tDiv.querySelector('.ticket-fields');
                            if (fields) fields.style.display = 'block';
                        } else {
                            tDiv.classList.add('ticket-collapsed');
                            tDiv.classList.remove('ticket-expanded');
                            if (tArrow) tArrow.style.transform = "rotate(0deg)";
                            const fields = tDiv.querySelector('.ticket-fields');
                            if (fields) fields.style.display = 'none';
                        }
                    }
                } else {
                    // Collapse this one
                    ticketDiv.classList.add('ticket-collapsed');
                    ticketDiv.classList.remove('ticket-expanded');
                    if (arrow) arrow.style.transform = "rotate(0deg)";
                    const fields = ticketDiv.querySelector('.ticket-fields');
                    if (fields) fields.style.display = 'none';
                }
            }

            // Expand a ticket (used for initial render)
            function expandTicket(idx, total) {
                for (let i = 1; i <= total; i++) {
                    const ticketDiv = document.getElementById(`ticket_${i}`);
                    const arrow = document.getElementById(`arrow_${i}`);
                    if (i === idx) {
                        ticketDiv.classList.remove('ticket-collapsed');
                        ticketDiv.classList.add('ticket-expanded');
                        if (arrow) arrow.style.transform = "rotate(180deg)";
                        const fields = ticketDiv.querySelector('.ticket-fields');
                        if (fields) fields.style.display = 'block';
                    } else {
                        ticketDiv.classList.add('ticket-collapsed');
                        ticketDiv.classList.remove('ticket-expanded');
                        if (arrow) arrow.style.transform = "rotate(0deg)";
                        const fields = ticketDiv.querySelector('.ticket-fields');
                        if (fields) fields.style.display = 'none';
                    }
                }
            }

            // Initial render
            document.addEventListener('DOMContentLoaded', function() {
                const numTicketsInput = document.getElementById('num_tickets');
                renderTicketForms(parseInt(numTicketsInput.value, 10));
                numTicketsInput.addEventListener('input', function() {
                    let val = parseInt(this.value, 10);
                    if (isNaN(val) || val < 1) val = 1;
                    if (val > 10) val = 10;
                    this.value = val;
                    renderTicketForms(val);
                });
            });


            // Related events data (example, can be replaced with PHP or AJAX)
            const relatedEvents = [
                {
                    img: "https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80",
                    title: "Tech Expo 2025",
                    date: "July 10, 2025 &bull; 10:00 AM",
                    venue: "Accra International Conference Centre",
                    price: "From GHS 80.00",
                    detailsUrl: "#"
                },
                {
                    img: "https://images.unsplash.com/photo-1515168833906-d2a3b82b3029?auto=format&fit=crop&w=400&q=80",
                    title: "Business Summit",
                    date: "August 5, 2025 &bull; 9:00 AM",
                    venue: "Accra International Conference Centre",
                    price: "From GHS 120.00",
                    detailsUrl: "#"
                },
                {
                    img: "https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=400&q=80",
                    title: "Art & Culture Night",
                    date: "September 2, 2025 &bull; 7:00 PM",
                    venue: "Accra International Conference Centre",
                    price: "From GHS 60.00",
                    detailsUrl: "#"
                },
                {
                    img: "https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=400&q=80",
                    title: "Startup Pitch Day",
                    date: "October 15, 2025 &bull; 2:00 PM",
                    venue: "Accra International Conference Centre",
                    price: "From GHS 90.00",
                    detailsUrl: "#"
                },
                {
                    img: "https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=400&q=80",
                    title: "Health & Wellness Fair",
                    date: "November 20, 2025 &bull; 8:00 AM",
                    venue: "Accra International Conference Centre",
                    price: "From GHS 50.00",
                    detailsUrl: "#"
                },
                {
                    img: "https://images.unsplash.com/photo-1509228468518-c5eeecbff44a?auto=format&fit=crop&w=400&q=80",
                    title: "Food Fest",
                    date: "December 12, 2025 &bull; 12:00 PM",
                    venue: "Accra International Conference Centre",
                    price: "From GHS 70.00",
                    detailsUrl: "#"
                }
            ];

            const eventsPerPage = 3;
            let currentPage = 1;

            function renderRelatedEvents(page) {
                const startIdx = (page - 1) * eventsPerPage;
                const endIdx = startIdx + eventsPerPage;
                const eventsToShow = relatedEvents.slice(startIdx, endIdx);

                const list = document.getElementById('related-events-list');
                list.innerHTML = eventsToShow.map(event => `
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition flex flex-col h-[480px]">
                        <img src="${event.img}" alt="${event.title}" class="w-full h-64 object-cover">
                        <div class="p-5 flex flex-col flex-1">
                            <h4 class="font-semibold text-lg text-indigo-700 mb-1">${event.title}</h4>
                            <div class="text-gray-600 text-sm mb-2">${event.date}</div>
                            <div class="text-gray-500 text-sm mb-3">${event.venue}</div>
                            <div class="text-green-600 font-semibold mb-2">${event.price}</div>
                            <div class="mt-auto flex flex-col gap-2">
                                <a href="${event.detailsUrl}" class="inline-block text-blue-600 hover:underline text-sm font-medium">View Details</a>
                                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg shadow transition w-full">Buy Ticket</button>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            function renderPagination() {
                const totalPages = Math.ceil(relatedEvents.length / eventsPerPage);
                const controls = document.getElementById('pagination-controls');
                let html = '';

                if (totalPages > 1) {
                    html += `<button class="mx-1 px-3 py-1 rounded ${currentPage === 1 ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-700'}" ${currentPage === 1 ? 'disabled' : ''} onclick="goToPage(${currentPage - 1})">&laquo; Prev</button>`;
                    for (let i = 1; i <= totalPages; i++) {
                        html += `<button class="mx-1 px-3 py-1 rounded ${i === currentPage ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-indigo-100'}" onclick="goToPage(${i})">${i}</button>`;
                    }
                    html += `<button class="mx-1 px-3 py-1 rounded ${currentPage === totalPages ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-700'}" ${currentPage === totalPages ? 'disabled' : ''} onclick="goToPage(${currentPage + 1})">Next &raquo;</button>`;
                }
                controls.innerHTML = html;
            }

            function goToPage(page) {
                const totalPages = Math.ceil(relatedEvents.length / eventsPerPage);
                if (page < 1 || page > totalPages) return;
                currentPage = page;
                renderRelatedEvents(currentPage);
                renderPagination();
            }

            // Expose goToPage globally for inline onclick
            window.goToPage = goToPage;

            document.addEventListener('DOMContentLoaded', function() {
                renderRelatedEvents(currentPage);
                renderPagination();
            });
