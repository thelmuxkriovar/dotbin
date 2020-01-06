#!/usr/bin/python3

import os
import sys

from evdev import InputDevice, categorize, ecodes
dev = InputDevice('/dev/input/event'+sys.argv[1])
dev.grab()

try:
	for event in dev.read_loop():
		if event.type == ecodes.EV_KEY:
			key = categorize(event)
			print(key, flush=True)
except KeyboardInterrupt:
	pass