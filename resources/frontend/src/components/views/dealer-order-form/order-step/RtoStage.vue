<template>

    <div>
        <div class="col-xs-12 panel panel-default">
            <div class="form">
                <!-- Common specific -->
                <h3>
                    <u>RENTER INFORMATION</u>
                </h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>First Name *</label>
                            <input type="text" class="form-control"
                                   :class="{'invalid': $v.customer.firstName.$error}"
                                   :value="customer.firstName"
                                   @input="updateCustomer({'firstName': $event.target.value})"
                                   @blur="$v.customer.firstName.$touch"
                                   placeholder="First Name">
                            <div v-if="$v.customer.firstName.$dirty && $v.customer.firstName.name === false" class="alert alert-danger" role="alert">Enter a valid First Name.</div>
                            <div v-if="$v.customer.firstName.$dirty && $v.customer.firstName.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Last Name *</label>
                            <input type="text"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.lastName.$error}"
                                   :value="customer.lastName"
                                   @blur="$v.customer.lastName.$touch"
                                   @input="updateCustomer({'lastName': $event.target.value})"
                                   placeholder="Last Name">
                            <div v-if="$v.customer.lastName.$dirty && $v.customer.lastName.required === false" class="alert alert-danger" role="alert">The Last Name is required.</div>
                            <div v-if="$v.customer.lastName.$dirty && $v.customer.lastName.name === false" class="alert alert-danger" role="alert">Enter correct Last Name.</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                        <label>Email *</label>
                        <input type="text"
                               class="form-control"
                               :class="{'invalid': $v.customer.email.$error}"
                               :value="customer.email"
                               @blur="$v.customer.email.$touch"
                               @input="updateCustomer({'email': $event.target.value})"
                               placeholder="Email">
                        <div v-if="$v.customer.email.$dirty && $v.customer.email.required === false" class="alert alert-danger" role="alert">The Email is required.</div>
                        <div v-if="$v.customer.email.$dirty && $v.customer.email.email === false" class="alert alert-danger" role="alert">Enter correct Email.</div>
                    </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <br/>
                            <div>
                                <label>
                                    <input type="checkbox"
                                           name="inlineRadioOptions"
                                           :value="true"
                                           :checked="renterData.emailInsteadOfMail === true"
                                           @change="updateRenter({'emailInsteadOfMail': $event.target.checked})">
                                    Email instead of Mail?
                                    <span class="help-block">Email for communications.</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone # *</label>
                            <Cleave :options='cleaveOptions'
                                    class="form-control"
                                    :class="{'invalid': $v.customer.phoneNumber.$error}"
                                    :value="customer.phoneNumber"
                                    @blur="$v.customer.phoneNumber.$touch"
                                    @input="updateCustomer({'phoneNumber': $event})"
                                    placeholder="Phone #"
                                    ref="customerPhoneNumber"/>
                            <div v-if="$v.customer.phoneNumber.$dirty && $v.customer.phoneNumber.required === false" class="alert alert-danger" role="alert">The Phone Number is required.</div>
                            <div v-if="$v.customer.phoneNumber.$dirty && $v.customer.phoneNumber.phone === false" class="alert alert-danger" role="alert">Enter correct Phone Number.</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address *</label>
                            <input type="text"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.address.$error}"
                                   :value="customer.address"
                                   @blur="$v.customer.address.$touch"
                                   @input="updateCustomer({'address': $event.target.value})"
                                   placeholder="Address">
                            <div v-if="$v.customer.address.$dirty && $v.customer.address.required === false" class="alert alert-danger" role="alert">The Address is required.</div>
                            <div v-if="$v.customer.address.$dirty && $v.customer.address.address === false" class="alert alert-danger" role="alert">Enter correct Address.</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City *</label>
                            <input type="text"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.city.$error}"
                                   :value="customer.city"
                                   @blur="$v.customer.city.$touch"
                                   @input="updateCustomer({'city': $event.target.value})"
                                   placeholder="City">
                            <div v-if="$v.customer.city.$dirty && $v.customer.city.required === false" class="alert alert-danger" role="alert">The City is required.</div>
                            <div v-if="$v.customer.city.$dirty && $v.customer.city.geo === false" class="alert alert-danger" role="alert">Enter correct City.</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>State *</label>
                            <input type="text"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.state.$error}"
                                   :value="customer.state"
                                   @blur="$v.customer.state.$touch"
                                   @input="updateCustomer({'state': $event.target.value})"
                                   placeholder="State">
                            <div v-if="$v.customer.state.$dirty && $v.customer.state.required === false" class="alert alert-danger" role="alert">The State is required.</div>
                            <div v-if="$v.customer.state.$dirty && $v.customer.state.geo === false" class="alert alert-danger" role="alert">Enter correct State.</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Zip *</label>
                            <Cleave :options='cleaveOptionsZip'
                                    class="form-control"
                                    :class="{'invalid': $v.customer.zip.$error}"
                                    :value="customer.zip"
                                    @blur="$v.customer.zip.$touch"
                                    @input="updateCustomer({'zip': $event})"
                                    placeholder="Zip"
                                    ref="customerZip"/>
                            <div v-if="$v.customer.zip.$dirty && $v.customer.zip.required === false" class="alert alert-danger" role="alert">The Zip is required.</div>
                            <div v-if="$v.customer.zip.$dirty && $v.customer.zip.zip === false" class="alert alert-danger" role="alert">Enter correct Zip.</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Building Location</label>
                        <div style="margin-top: 0px">
                            <label>
                                <input type="checkbox"
                                       :checked="customer.buildingInSameAddress"
                                       @change="updateCustomer({'buildingInSameAddress': $event.target.checked})">
                                Building will be located at same address as above
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="customer.buildingInSameAddress === false">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address *</label>
                            <input type="text"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.buildingLocationAddress.$error}"
                                   :value="customer.buildingLocationAddress"
                                   @blur="$v.customer.buildingLocationAddress.$touch"
                                   @input="updateCustomer({'buildingLocationAddress': $event.target.value})"
                                   placeholder="Address">
                            <div v-if="$v.customer.buildingLocationAddress.$dirty && $v.customer.buildingLocationAddress.required === false" class="alert alert-danger" role="alert">The Address is required.</div>
                            <div v-if="$v.customer.buildingLocationAddress.$dirty && $v.customer.buildingLocationAddress.address === false" class="alert alert-danger" role="alert">Enter correct Address.</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City *</label>
                            <input type="text"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.buildingLocationCity.$error}"
                                   :value="customer.buildingLocationCity"
                                   @blur="$v.customer.buildingLocationCity.$touch"
                                   @input="updateCustomer({'buildingLocationCity': $event.target.value})"
                                   placeholder="City">
                            <div v-if="$v.customer.buildingLocationCity.$dirty && $v.customer.buildingLocationCity.required === false" class="alert alert-danger" role="alert">The City is required.</div>
                            <div v-if="$v.customer.buildingLocationCity.$dirty && $v.customer.buildingLocationCity.geo === false" class="alert alert-danger" role="alert">Enter correct City.</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>State *</label>
                            <input type="text"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.buildingLocationState.$error}"
                                   :value="customer.buildingLocationState"
                                   @blur="$v.customer.buildingLocationState.$touch"
                                   @input="updateCustomer({'buildingLocationState': $event.target.value})"
                                   placeholder="State">
                            <div v-if="$v.customer.buildingLocationState.$dirty && $v.customer.buildingLocationState.required === false" class="alert alert-danger" role="alert">The State is required.</div>
                            <div v-if="$v.customer.buildingLocationState.$dirty && $v.customer.buildingLocationState.geo === false" class="alert alert-danger" role="alert">Enter correct State.</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Zip *</label>
                            <Cleave :options='cleaveOptionsZip'
                                    class="form-control"
                                    :class="{'invalid': $v.customer.buildingLocationZip.$error}"
                                    :value="customer.buildingLocationZip"
                                    @blur="$v.customer.buildingLocationZip.$touch"
                                    @input="updateCustomer({'buildingLocationZip': $event})"
                                    placeholder="Zip"
                                    ref="customerBuildingLocationZip"/>
                            <div v-if="$v.customer.buildingLocationZip.$dirty && $v.customer.buildingLocationZip.required === false" class="alert alert-danger" role="alert">The Zip is required.</div>
                            <div v-if="$v.customer.buildingLocationZip.$dirty && $v.customer.buildingLocationZip.zip === false" class="alert alert-danger" role="alert">Enter correct Zip.</div>
                        </div>
                    </div>
                </div>

                <!------------- RTO specific ------------->
                <template v-if="orderPaymentType === 'rto'">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Property Status</label>
                            <div class="form-group">
                                <div>
                                    <label>
                                        <input type="radio"
                                               :value="'own'"
                                               :checked="renterData.propertyOwnership === 'own'"
                                               @change="updateRenter({'propertyOwnership': $event.target.value})">
                                        Own
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input
                                                type="radio"
                                                :value="'rent'"
                                                :checked="renterData.propertyOwnership === 'rent'"
                                                @change="updateRenter({'propertyOwnership': $event.target.value})">
                                        Rent
                                    </label>
                                </div>
                                <div v-if="$v.renterData.propertyOwnership.$dirty && $v.renterData.propertyOwnership.propertyStatus === false" class="alert alert-danger" role="alert">Property Status should be 'own' or 'rent'.</div>
                                <div v-if="$v.renterData.propertyOwnership.$dirty && $v.renterData.propertyOwnership.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-show="renterData.propertyOwnership === 'rent'">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Landlord *</label>
                                <input :value="renterData.landlordFullName" type="text" class="form-control" :class="{'invalid': $v.renterData.landlordFullName.$error}"
                                       @input="updateRenter({'landlordFullName': $event.target.value})"
                                       @blur="$v.renterData.landlordFullName.$touch"
                                       placeholder="Landlord Full Name">
                                <div v-if="$v.renterData.landlordFullName.$dirty && $v.renterData.landlordFullName.name === false" class="alert alert-danger" role="alert">Enter a valid Landlord Full Name.</div>
                                <div v-if="$v.renterData.landlordFullName.$dirty && $v.renterData.landlordFullName.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                                <span class="help-block">Only if renting property where building is located.</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Phone # *</label>
                                <Cleave :options='cleaveOptions' :value="renterData.landlordPhoneNumber" class="form-control" :class="{'invalid': $v.renterData.landlordPhoneNumber.$error}"
                                        @input="updateRenter({'landlordPhoneNumber': $event})"
                                        @blur="$v.renterData.landlordPhoneNumber.$touch"
                                        placeholder="Phone #"
                                        ref="renterLandlordPhoneNumber"/>
                                <div v-if="$v.renterData.landlordPhoneNumber.$dirty && $v.renterData.landlordPhoneNumber.phone === false" class="alert alert-danger" role="alert">Enter a valid Phone number.</div>
                                <div v-if="$v.renterData.landlordPhoneNumber.$dirty && $v.renterData.landlordPhoneNumber.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                    </div>

                    <!------------- Collapse ------------->
                    <p>
                        <button class="btn btn-info" @click="showAdditionalPhoneNumber = !showAdditionalPhoneNumber">
                            <span v-if="showAdditionalPhoneNumber">Collapse Phone Number Info</span>
                            <span v-else>Expand Phone Number Info</span>
                        </button>
                    </p>

                    <div v-show="showAdditionalPhoneNumber">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cell # *</label>
                                    <input type="text" class="form-control" :value="customer.phoneNumber" :class="{'invalid': $v.customer.phoneNumber.$error}"
                                           @input="updateCustomer({'phoneNumber': $event.target.value})"
                                           @blur="$v.customer.phoneNumber.$touch"
                                           placeholder="Cell Number">
                                    <div v-if="$v.customer.phoneNumber.$dirty && $v.customer.phoneNumber.phone === false" class="alert alert-danger" role="alert">Enter a valid Cell phone number.</div>
                                    <div v-if="$v.customer.phoneNumber.$dirty && $v.customer.phoneNumber.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div>
                                        <label>
                                            <input type="radio"
                                                   :value="'yes'"
                                                   :checked="renterData.textAllowed1 === 'yes'"
                                                   @change="updateRenter({'textAllowed1': $event.target.value})">
                                            Text Yes?
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input
                                                    type="radio"
                                                    :value="'no'"
                                                    :checked="renterData.textAllowed1 === 'no'"
                                                    @change="updateRenter({'textAllowed1': $event.target.value})">
                                            Text No?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cell #</label>
                                    <input type="text" class="form-control" :value="renterData.cellPhoneNumber2" :class="{'invalid': $v.renterData.cellPhoneNumber2.$error}"
                                           @input="updateRenter({'cellPhoneNumber2': $event.target.value})"
                                           @blur="$v.renterData.cellPhoneNumber2.$touch"
                                           placeholder="Cell Number">
                                    <div v-if="$v.renterData.cellPhoneNumber2.$dirty && $v.renterData.cellPhoneNumber2.phone === false" class="alert alert-danger" role="alert">Enter a valid Cell phone number.</div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div>
                                        <label>
                                            <input type="radio"
                                                   :value="'yes'"
                                                   :checked="renterData.textAllowed2 === 'yes'"
                                                   @change="updateRenter({'textAllowed2': $event.target.value})">
                                            Text Yes?
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="radio"
                                                   :value="'no'"
                                                   :checked="renterData.textAllowed2 === 'no'"
                                                   @change="updateRenter({'textAllowed2': $event.target.value})">
                                            Text No?
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <ol>
                                    <li>By choosing Yes, you consent to receive text messages sent by an automatic telephone
                                        dialing system.
                                    </li>
                                    <li>Consent to these terms is not a condition of purchase.</li>
                                </ol>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Home #</label>
                                    <input type="text" class="form-control" :value="renterData.homePhoneNumber" :class="{'invalid': $v.renterData.homePhoneNumber.$error}"
                                           @input="updateRenter({'homePhoneNumber': $event.target.value})"
                                           @blur="$v.renterData.homePhoneNumber.$touch"
                                           placeholder="Home #">
                                    <div v-if="$v.renterData.homePhoneNumber.$dirty && $v.renterData.homePhoneNumber.phone === false" class="alert alert-danger" role="alert">Enter a valid Home phone number.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------- /Collapse ------------->


                    <h4>WORK INFORMATION</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Renter's Employer *</label>
                                <input type="text" class="form-control" :value="renterData.renterEmployer" :class="{'invalid': $v.renterData.renterEmployer.$error}"
                                       @input="updateRenter({'renterEmployer': $event.target.value})"
                                       @blur="$v.renterData.renterEmployer.$touch"
                                       placeholder="Renter's Employer">
                                <div v-if="$v.renterData.renterEmployer.$dirty && $v.renterData.renterEmployer.bname === false" class="alert alert-danger" role="alert">Enter a valid Renter's Employer.</div>
                                <div v-if="$v.renterData.renterEmployer.$dirty && $v.renterData.renterEmployer.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Phone *</label>
                                <Cleave :options='cleaveOptions' class="form-control"
                                        :value="renterData.renterEmployerPhoneNumber"
                                        :class="{'invalid': $v.renterData.renterEmployerPhoneNumber.$error}"
                                        @input="updateRenter({'renterEmployerPhoneNumber': $event})"
                                        @blur="$v.renterData.renterEmployerPhoneNumber.$touch"
                                        placeholder="Phone"
                                        ref="renterEmployerPhoneNumber"/>
                                <div v-if="$v.renterData.renterEmployerPhoneNumber.$dirty && $v.renterData.renterEmployerPhoneNumber.phone === false" class="alert alert-danger" role="alert">Enter a valid Phone number.</div>
                                <div v-if="$v.renterData.renterEmployerPhoneNumber.$dirty && $v.renterData.renterEmployerPhoneNumber.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ext</label>
                                <input type="text" class="form-control" :value="renterData.renterEmployerPhoneExtension" :class="{'invalid': $v.renterData.renterEmployerPhoneExtension.$error}"
                                       @input="updateRenter({'renterEmployerPhoneExtension': $event.target.value})"
                                       placeholder="Ext">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Renter's Supervisor *</label>
                                <input type="text" class="form-control" :value="renterData.renterSupervisor" :class="{'invalid': $v.renterData.renterSupervisor.$error}"
                                       @input="updateRenter({'renterSupervisor': $event.target.value})"
                                       @blur="$v.renterData.renterSupervisor.$touch"
                                       placeholder="Renter's Supervisor">
                                <div v-if="$v.renterData.renterSupervisor.$dirty && $v.renterData.renterSupervisor.name === false" class="alert alert-danger" role="alert">Enter a valid Renter's Supervisor.</div>
                                <div v-if="$v.renterData.renterSupervisor.$dirty && $v.renterData.renterSupervisor.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Occupation *</label>
                                <input type="text" class="form-control" :value="renterData.renterSupervisorOccupation" :class="{'invalid': $v.renterData.renterSupervisorOccupation.$error}"
                                       @input="updateRenter({'renterSupervisorOccupation': $event.target.value})"
                                       @blur="$v.renterData.renterSupervisorOccupation.$touch"
                                       placeholder="Occupation">
                                <div v-if="$v.renterData.renterSupervisorOccupation.$dirty && $v.renterData.renterSupervisorOccupation.name === false" class="alert alert-danger" role="alert">Enter a valid Occupation.</div>
                                <div v-if="$v.renterData.renterSupervisorOccupation.$dirty && $v.renterData.renterSupervisorOccupation.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                    </div>

                    <!------------- Collapse ------------->
                    <h3>
                        <u>CO-RENTER INFORMATION</u>*
                        <small>(all fields are required if Co-Renter name presented)</small>
                    </h3>
                    <p>
                        <button class="btn btn-info" @click="showCoRenterInfo = !showCoRenterInfo">
                            <span v-if="showCoRenterInfo">Collapse Co-Renter Info</span>
                            <span v-else>Expand Co-Renter Info</span>
                        </button>
                    </p>

                    <div v-show="showCoRenterInfo">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Co-Renter First Name</label>
                                    <input type="text" class="form-control" :value="renterData.coRenterFirstName" :class="{'invalid': $v.renterData.coRenterFirstName.$error}"
                                           @input="updateRenter({'coRenterFirstName': $event.target.value})"
                                           @blur="$v.renterData.coRenterFirstName.$touch"
                                           placeholder="Full Name">
                                    <div v-if="$v.renterData.coRenterFirstName.$dirty && $v.renterData.coRenterFirstName.name === false" class="alert alert-danger" role="alert">Enter a valid Co-Renter First Name.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Co-Renter Last Name</label>
                                    <input type="text" class="form-control" initial="off" :value="renterData.coRenterLastName" :class="{'invalid': $v.renterData.coRenterLastName.$error}"
                                           @input="updateRenter({'coRenterLastName': $event.target.value})"
                                           @blur="$v.renterData.coRenterLastName.$touch"
                                           placeholder="Full Name">
                                    <div v-if="$v.renterData.coRenterLastName.$dirty && $v.renterData.coRenterLastName.name === false" class="alert alert-danger" role="alert">Enter a valid Co-Renter Last Name.
                                    </div>
                                </div>
                            </div>
                        </div>


                        <h4>WORK INFORMATION</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Co-Renter's Employer</label>
                                    <input type="text" class="form-control" :value="renterData.coRenterEmployer" :class="{'invalid': $v.renterData.coRenterEmployer.$error}"
                                           @input="updateRenter({'coRenterEmployer': $event.target.value})"
                                           @blur="$v.renterData.coRenterEmployer.$touch"
                                           placeholder="Co-Renter's Employer">
                                    <div v-if="$v.renterData.coRenterEmployer.$dirty && $v.renterData.coRenterEmployer.dsn === false" class="alert alert-danger" role="alert">Enter a valid Co-Renter's Employer.</div>
                                    <div v-if="$v.renterData.coRenterEmployer.$dirty && $v.renterData.coRenterEmployer.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <Cleave :options='cleaveOptions' class="form-control" :value="renterData.coRenterEmployerPhoneNumber" :class="{'invalid': $v.renterData.coRenterEmployerPhoneNumber.$error}"
                                            @input="updateRenter({'coRenterEmployerPhoneNumber': $event})"
                                            @blur="$v.renterData.coRenterEmployerPhoneNumber.$touch"
                                            placeholder="Phone"
                                            ref="renterCoRenterEmployerPhoneNumber"/>
                                    <div v-if="$v.renterData.coRenterEmployerPhoneNumber.$dirty && $v.renterData.coRenterEmployerPhoneNumber.phone === false" class="alert alert-danger" role="alert">Enter a valid Phone number.</div>
                                    <div v-if="$v.renterData.coRenterEmployerPhoneNumber.$dirty && $v.renterData.coRenterEmployerPhoneNumber.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Ext</label>
                                    <input type="text" class="form-control" :value="renterData.coRenterEmployerPhoneExtension" :class="{'invalid': $v.renterData.coRenterEmployerPhoneExtension.$error}"
                                           @input="updateRenter({'coRenterEmployerPhoneExtension': $event.target.value})"
                                           @blur="$v.renterData.coRenterEmployerPhoneExtension.$touch"
                                           placeholder="Ext">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Co-Renter's Supervisor</label>
                                    <input type="text" class="form-control" :value="renterData.coRenterSupervisor" :class="{'invalid': $v.renterData.coRenterSupervisor.$error}"
                                           @input="updateRenter({'coRenterSupervisor': $event.target.value})"
                                           @blur="$v.renterData.coRenterSupervisor.$touch"
                                           placeholder="Co-Renter's Supervisor">
                                    <div v-if="$v.renterData.coRenterSupervisor.$dirty && $v.renterData.coRenterSupervisor.name === false" class="alert alert-danger" role="alert">Enter a valid Co-Renter's Supervisor.</div>
                                    <div v-if="$v.renterData.coRenterSupervisor.$dirty && $v.renterData.coRenterSupervisor.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Occupation</label>
                                    <input type="text" class="form-control" :value="renterData.coRenterSupervisorOccupation" :class="{'invalid': $v.renterData.coRenterSupervisorOccupation.$error}"
                                           @input="updateRenter({'coRenterSupervisorOccupation': $event.target.value})"
                                           @blur="$v.renterData.coRenterSupervisorOccupation.$touch"
                                           placeholder="Occupation">
                                    <div v-if="$v.renterData.coRenterSupervisorOccupation.$dirty && $v.renterData.coRenterSupervisorOccupation.name === false" class="alert alert-danger" role="alert">Enter a valid Occupation.</div>
                                    <div v-if="$v.renterData.coRenterSupervisorOccupation.$dirty && $v.renterData.coRenterSupervisorOccupation.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!------------- /Collapse ------------->

                    <h3><u>REFERENCES</u></h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Name *</label>
                                <input type="text" class="form-control" :value="renterData.reference1Name" :class="{'invalid': $v.renterData.reference1Name.$error}"
                                       @input="updateRenter({'reference1Name': $event.target.value})"
                                       @blur="$v.renterData.reference1Name.$touch"
                                       placeholder="Name">
                                <div v-if="$v.renterData.reference1Name.$dirty && $v.renterData.reference1Name.name === false" class="alert alert-danger" role="alert">Enter a valid Name.</div>
                                <div v-if="$v.renterData.reference1Name.$dirty && $v.renterData.reference1Name.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Relationship *</label>
                                <input type="text" class="form-control" :value="renterData.reference1Relationship" :class="{'invalid': $v.renterData.reference1Relationship.$error}"
                                       @input="updateRenter({'reference1Relationship': $event.target.value})"
                                       @blur="$v.renterData.reference1Relationship.$touch"
                                       placeholder="Relationship">
                                <span class="help-block">Example: Friend, Cousin, Father, Mother.</span>
                                <div v-if="$v.renterData.reference1Relationship.$dirty && $v.renterData.reference1Relationship.name === false" class="alert alert-danger" role="alert">Enter a valid Relationship.</div>
                                <div v-if="$v.renterData.reference1Relationship.$dirty && $v.renterData.reference1Relationship.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Phone # *</label>
                                <Cleave :options='cleaveOptions' class="form-control"
                                        :value="renterData.reference1PhoneNumber"
                                        :class="{'invalid': $v.renterData.reference1PhoneNumber.$error}"
                                        @input="updateRenter({'reference1PhoneNumber': $event})"
                                        @blur="$v.renterData.reference1PhoneNumber.$touch"
                                        placeholder="Phone #"
                                        ref="renterReference1PhoneNumber"/>
                                <div v-if="$v.renterData.reference1PhoneNumber.$dirty && $v.renterData.reference1PhoneNumber.phone === false" class="alert alert-danger" role="alert">Enter a valid Phone number.</div>
                                <div v-if="$v.renterData.reference1PhoneNumber.$dirty && $v.renterData.reference1PhoneNumber.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Address *</label>
                                <input type="text" class="form-control" :value="renterData.reference1Address" :class="{'invalid': $v.renterData.reference1Address.$error}"
                                       @input="updateRenter({'reference1Address': $event.target.value})"
                                       @blur="$v.renterData.reference1Address.$touch"
                                       placeholder="Address">
                                <div v-if="$v.renterData.reference1Address.$dirty && $v.renterData.reference1Address.address === false" class="alert alert-danger" role="alert">Enter a valid Address.</div>
                                <div v-if="$v.renterData.reference1Address.$dirty && $v.renterData.reference1Address.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>City *</label>
                                <input type="text" class="form-control" :value="renterData.reference1City" :class="{'invalid': $v.renterData.reference1City.$error}"
                                       @input="updateRenter({'reference1City': $event.target.value})"
                                       @blur="$v.renterData.reference1City.$touch"
                                       placeholder="City">
                                <div v-if="$v.renterData.reference1City.$dirty && $v.renterData.reference1City.geo === false" class="alert alert-danger" role="alert">Enter a valid City.</div>
                                <div v-if="$v.renterData.reference1City.$dirty && $v.renterData.reference1City.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>State *</label>
                                <input type="text" class="form-control" :value="renterData.reference1State" :class="{'invalid': $v.renterData.reference1State.$error}"
                                       @input="updateRenter({'reference1State': $event.target.value})"
                                       @blur="$v.renterData.reference1State.$touch"
                                       placeholder="State">
                                <div v-if="$v.renterData.reference1State.$dirty && $v.renterData.reference1State.geo === false" class="alert alert-danger" role="alert">Enter a valid City.</div>
                                <div v-if="$v.renterData.reference1State.$dirty && $v.renterData.reference1State.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Zip *</label>
                                <Cleave :options='cleaveOptionsZip'
                                        class="form-control" :value="renterData.reference1Zip" :class="{'invalid': $v.renterData.reference1Zip.$error}"
                                        @input="updateRenter({'reference1Zip': $event})"
                                        @blur="$v.renterData.reference1Zip.$touch"
                                        placeholder="Zip"
                                        ref="renterReference1Zip"/>
                                <div v-if="$v.renterData.reference1Zip.$dirty && $v.renterData.reference1Zip.zip === false" class="alert alert-danger" role="alert">Enter a valid Zip (only 5 number allowed).</div>
                                <div v-if="$v.renterData.reference1Zip.$dirty && $v.renterData.reference1Zip.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Name *</label>
                                <input type="text" class="form-control" :value="renterData.reference2Name" :class="{'invalid': $v.renterData.reference2Name.$error}"
                                       @input="updateRenter({'reference2Name': $event.target.value})"
                                       @blur="$v.renterData.reference2Name.$touch"
                                       placeholder="Name">
                                <div v-if="$v.renterData.reference2Name.$dirty && $v.renterData.reference2Name.name === false" class="alert alert-danger" role="alert">Enter a valid Name.</div>
                                <div v-if="$v.renterData.reference2Name.$dirty && $v.renterData.reference2Name.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Relationship *</label>
                                <input type="text" class="form-control" :value="renterData.reference2Relationship" :class="{'invalid': $v.renterData.reference2Relationship.$error}"
                                       @input="updateRenter({'reference2Relationship': $event.target.value})"
                                       @blur="$v.renterData.reference2Relationship.$touch"
                                       placeholder="Relationship">
                                <span class="help-block">Example: Friend, Cousin, Father, Mother.</span>
                                <div v-if="$v.renterData.reference2Relationship.$dirty && $v.renterData.reference2Relationship.name === false" class="alert alert-danger" role="alert">Enter a valid Relationship.</div>
                                <div v-if="$v.renterData.reference2Relationship.$dirty && $v.renterData.reference2Relationship.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Phone # *</label>
                                <Cleave :options='cleaveOptions' class="form-control"
                                        :value="renterData.reference2PhoneNumber"
                                        :class="{'invalid': $v.renterData.reference2PhoneNumber.$error}"
                                        @input="updateRenter({'reference2PhoneNumber': $event})"
                                        @blur="$v.renterData.reference2PhoneNumber.$touch"
                                        placeholder="Phone #"
                                        ref="renterReference2PhoneNumber"/>
                                <!--<input >-->
                                <div v-if="$v.renterData.reference2PhoneNumber.$dirty && $v.renterData.reference2PhoneNumber.phone === false" class="alert alert-danger" role="alert">Enter a valid Phone number.</div>
                                <div v-if="$v.renterData.reference2PhoneNumber.$dirty && $v.renterData.reference2PhoneNumber.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Address *</label>
                                <input type="text" class="form-control" :value="renterData.reference2Address" :class="{'invalid': $v.renterData.reference2Address.$error}"
                                       @input="updateRenter({'reference2Address': $event.target.value})"
                                       @blur="$v.renterData.reference2Address.$touch"
                                       placeholder="Address">
                                <div v-if="$v.renterData.reference2Address.$dirty && $v.renterData.reference2Address.address === false" class="alert alert-danger" role="alert">Enter a valid Address</div>
                                <div v-if="$v.renterData.reference2Address.$dirty && $v.renterData.reference2Address.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>City *</label>
                                <input type="text" class="form-control" :value="renterData.reference2City" :class="{'invalid': $v.renterData.reference2City.$error}"
                                       @input="updateRenter({'reference2City': $event.target.value})"
                                       @blur="$v.renterData.reference2City.$touch"
                                       placeholder="City">
                                <div v-if="$v.renterData.reference2City.$dirty && $v.renterData.reference2City.geo === false" class="alert alert-danger" role="alert">Enter a valid City.</div>
                                <div v-if="$v.renterData.reference2City.$dirty && $v.renterData.reference2City.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>State *</label>
                                <input type="text" class="form-control" :value="renterData.reference2State" :class="{'invalid': $v.renterData.reference2State.$error}"
                                       @input="updateRenter({'reference2State': $event.target.value})"
                                       @blur="$v.renterData.reference2State.$touch"
                                       placeholder="State">
                                <div v-if="$v.renterData.reference2State.$dirty && $v.renterData.reference2State.geo === false" class="alert alert-danger" role="alert">Enter a valid City.</div>
                                <div v-if="$v.renterData.reference2State.$dirty && $v.renterData.reference2State.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Zip *</label>
                                <Cleave :options='cleaveOptionsZip'
                                        class="form-control" :value="renterData.reference2Zip" :class="{'invalid': $v.renterData.reference2Zip.$error}"
                                        @input="updateRenter({'reference2Zip': $event})"
                                        @blur="$v.renterData.reference2Zip.$touch"
                                        placeholder="Zip"
                                        ref="renterReference2Zip"/>
                                <div v-if="$v.renterData.reference2Zip.$dirty && $v.renterData.reference2Zip.zip === false" class="alert alert-danger" role="alert">Enter a valid Zip (only 5 number allowed).</div>
                                <div v-if="$v.renterData.reference2Zip.$dirty && $v.renterData.reference2Zip.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Prev/Next buttons -->
            <div class="row" style="margin-bottom: 1em;">
                <div class="col-xs-12 text-center">
                    <button type="button" class="btn btn-default" @click.prevent="goToStep('previous')"><i
                            class="fa fa-arrow-left fa-fw"></i>Previous
                    </button>
                    <button @click="goToStep('next')" type="button" class="btn btn-default">
                        Next <i class="fa fa-arrow-right fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import {mapActions, mapGetters} from 'vuex'

    import vuelidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import rtoStageValidation from 'src/validations/dealer-order-form/rto-stage.validation.js'
    import Cleave from 'vue-cleave/src/Cleave.vue'
    import 'cleave.js/dist/addons/cleave-phone.mx'

    let coRenterFields = [
        'coRenterFirstName',
        'coRenterLastName',
        'coRenterEmployer',
        'coRenterEmployerPhoneNumber',
        'coRenterEmployerPhoneExtension',
        'coRenterSupervisor',
        'coRenterSupervisorOccupation'
    ]

    let additionalPhoneNumberFields = [
        'textAllowed1',
        'cellPhoneNumber2',
        'textAllowed2',
        'homePhoneNumber'
    ]

    export default {
        name: 'rto-stage',
        mixins: [vuelidateAnyerror],
        components: {Cleave},
        validations: rtoStageValidation,
        data: function () {
            return {
                showCoRenterInfo: false,
                showAdditionalPhoneNumber: false,
                formatedValue: null,
                cleaveOptions: {
                    delimiter: '-',
                    blocks: [3, 3, 4],
                    numericOnly: true
                },
                cleaveOptionsZip: {
                    blocks: [5],
                    numericOnly: true
                },
                cleaveOptionsDate: {
                    date: true,
                    datePattern: ['m', 'd', 'Y']
                },
                date: {
                    placeholder: 'MM/DD/YYYY',
                    format: 'MM/dd/yyyy',
                    style: {
                        width: '100%'
                    }
                }
            }
        },
        created() {
            this.$watch('$anyerror', (value) => {
                this.$parent.revalidate()

                // Co-Renter Info
                let invalidCoRenter = _.find(coRenterFields, field => this.$v[field] && this.$v[field].$error)
                if (invalidCoRenter) {
                    this.showCoRenterInfo = invalidCoRenter
                }

                // Co-Renter Info
                let invalidAdditionalFields = _.find(additionalPhoneNumberFields, field => this.$v[field] && this.$v[field].$error)
                if (invalidAdditionalFields) {
                    this.showAdditionalPhoneNumber = invalidAdditionalFields
                }
            })

            this.$bus.$on('dofOrderLoaded', () => {
                // customer
                this.$refs.customerPhoneNumber.cleave.setRawValue(this.customer.phoneNumber)
                this.$refs.customerZip.cleave.setRawValue(this.customer.zip)

                if (this.$refs.customerBuildingLocationZip) {
                    this.$refs.customerBuildingLocationZip.cleave.setRawValue(this.customer.buildingLocationZip)
                }

                // renter
                this.$refs.renterLandlordPhoneNumber.cleave.setRawValue(this.renterData.landlordPhoneNumber)
                this.$refs.renterEmployerPhoneNumber.cleave.setRawValue(this.renterData.renterEmployerPhoneNumber)
                this.$refs.renterCoRenterEmployerPhoneNumber.cleave.setRawValue(this.renterData.coRenterEmployerPhoneNumber)
                this.$refs.renterReference1PhoneNumber.cleave.setRawValue(this.renterData.reference1PhoneNumber)
                this.$refs.renterReference2PhoneNumber.cleave.setRawValue(this.renterData.reference2PhoneNumber)
                this.$refs.renterReference1Zip.cleave.setRawValue(this.renterData.reference1Zip)
                this.$refs.renterReference2Zip.cleave.setRawValue(this.renterData.reference2Zip)
            })

            this.$on('show-step', this.onShowStep)
        },
        computed: {
            ...mapGetters({
                customer: 'dealerOrderForm/orderCustomer',
                renterData: 'dealerOrderForm/orderRenter',
                orderStateMode: 'dealerOrderForm/orderStateMode',
                orderPaymentType: 'dealerOrderForm/orderPaymentType'
            })
        },
        methods: {
            ...mapActions({
                updateOrderCustomer: 'dealerOrderForm/updateOrderCustomer',
                updateOrderRenter: 'dealerOrderForm/updateOrderRenter'
            }),
            onShowStep() {
                let notEmptyCoRenter = _.find(coRenterFields, field => (this.renterData[field] !== null && this.renterData[field] !== ''))
                this.showCoRenterInfo = notEmptyCoRenter

                let notEmptyAdditionalFields = _.find(additionalPhoneNumberFields, field => (
                    this.renterData[field] !== null &&
                    this.renterData[field] !== '' &&
                    this.renterData[field] !== 'no')
                )
                this.showAdditionalPhoneNumber = notEmptyAdditionalFields
            },
            goToStep(step, options) {
                return this.$parent.goToStep(step, options)
            },
            updateCustomer(object) {
                this.updateOrderCustomer(object)

                _.each(object, (val, key) => {
                    if (this.$v[key]) this.$v[key].$touch()
                })
            },
            updateRenter(object) {
                this.updateOrderRenter(object)

                _.each(object, (val, key) => {
                    if (this.$v[key]) this.$v[key].$touch()
                })
            },
            nextStep(direction) {
                return this.$parent.nextStep(direction)
            }
        }
    }
</script>

<style type="text/css">
    .form-group {
        margin-bottom: 15px!important;
    }
</style>