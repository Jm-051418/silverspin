package com.shippingsystem.shippingservice.repository;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import com.shippingsystem.shippingservice.model.Shippings;

@Repository
public interface ShippingRepository extends JpaRepository<Shippings, Integer>{
    
}
