#!/bin/bash
echo "Stopping Flask on EC2 Instance..."
pkill -f *app.py
