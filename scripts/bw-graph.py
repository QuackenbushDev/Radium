#!/bin/python

import sys
from graph import graph

def convert_string_to_list(text):
    return text.split("|")

if __name__ == "__main__":
    args = sys.argv
    title = args[1]
    labels = convert_string_to_list(args[2])
    data = [
        [args[3], convert_string_to_list(args[4])],
        [args[5], convert_string_to_list(args[6])]
    ]

    graph(title, labels, data)
