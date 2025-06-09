   <div class="row">
       <div class="col-sm-12">
           <div class="card">
               <div class="card-body">
                   <div class="col-lg-12">
                       <table class="table">
                           <tr>
                               <th>Property Name</th>
                               <td>
                                   {{ $maintenanceRequest->property ? $maintenanceRequest->property->name : '---' }}
                               </td>
                           </tr>
                           <tr>
                               <th>Property Address</th>
                               <td>
                                   <h5 class="text-muted font-bold mb-3">Location Details</h5>
                                   <p class="mb-2"><strong>Location:</strong><br>
                                       {{ $maintenanceRequest->property->location }}</p>
                                   <p class="mb-2"><strong>City:</strong> {{ $maintenanceRequest->property->city }}</p>
                                   <p class="mb-2"><strong>Locality:</strong>
                                       {{ $maintenanceRequest->property->locality }}</p>
                                   <p class="mb-2"><strong>Sublocality:</strong>
                                       {{ $maintenanceRequest->property->sub_locality }}</p>
                                   <p class="mb-2"><strong>Landmark:</strong>
                                       {{ $maintenanceRequest->property->landmark }}</p>
                               </td>
                           </tr>
                           <tr>
                               <th>Unit Name</th>
                               <td>
                                   {{ $maintenanceRequest->unit->name }}
                               </td>
                           </tr>
                           <tr>
                               <th>Reg No</th>
                               <td>{{ $maintenanceRequest->unit ? $maintenanceRequest->unit->registration_no : '---' }}
                               </td>
                           </tr>
                       </table>
                   </div>
                   <div class="col-lg-12 mt-2 ">
                       <h6 class="my-3">Mainataince Request Details</h6>
                       <table class="table">
                           <tr>
                               <th>Maintainer Name</th>
                               <td>
                                   {{ $maintenanceRequest->maintainer ? $maintenanceRequest->maintainer->name : '---' }}
                               </td>
                           </tr>
                           <tr>
                               <th>Issue Type</th>
                               <td>
                                   {{ $maintenanceRequest->issue ? $maintenanceRequest->issue->name : '---' }}
                               </td>
                           </tr>
                           <tr>
                               <th>Notes</th>
                               <td>
                                   {{ $maintenanceRequest->notes }}
                               </td>
                           </tr>
                           <tr>
                               <th>Attachments</th>
                               <td>
                                   <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                       @if (isset($maintenanceRequest))
                                           @foreach ($maintenanceRequest->maintenanceRequestAttachments ?? [] as $key => $image)
                                               <div class="flex flex-col relative existing-data-box">
                                                   <div class="relative group border rounded-lg overflow-hidden ">
                                                       <img src="{{ asset('storage/' . $image->file_url) }}"
                                                           class="thumbnail" style="height: 100px;width: 100%;"
                                                           alt="Uploaded Image">
                                                   </div>
                                               </div>
                                           @endforeach
                                       @endif
                                   </div>
                               </td>
                           </tr>
                           <tr>
                               <th>Requested At</th>
                               <td>
                                   {{ dateFormat($maintenanceRequest->request_date) }}
                               </td>
                           </tr>
                           <tr>
                               <th>Status</th>
                               <td>
                                   <span class="text-capitalize fw-bold">{{ $maintenanceRequest->status }}</span>
                               </td>
                           </tr>
                       </table>

                   </div>
               </div>
           </div>
       </div>
   </div>
